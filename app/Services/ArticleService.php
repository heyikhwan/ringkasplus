<?php

namespace App\Services;

use App\Exceptions\AppException;
use Illuminate\Support\Str;
use App\Traits\ActivityLogUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Traits\UploadFileTrait;
use Yajra\DataTables\Facades\DataTables;

class ArticleService
{
    use ActivityLogUser, UploadFileTrait;

    protected $logName = 'Artikel';

    protected $articleRepository;
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function datatable($permission_name)
    {
        $query = $this->articleRepository->getBaseQuery(['author', 'categories']);

        if (request()->filled('category')) {
            $query->whereHas('categories', function ($q) {
                $q->where('id', request()->category);
            });
        }

        if (request()->filled('status')) {
            $query->where('status', request()->status);
        }

        if (request()->filled('published_at')) {
            $query->where('published_at', 'LIKE', '%' . request()->published_at . '%');
        }

        if (request()->filled('is_featured') && request()->is_featured) {
            $query->where('is_featured', request()->is_featured);
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function ($row) use ($permission_name) {
                $primaryKey = encode($row->id);

                $authUser   = auth()->user();
                $items      = [];

                if ($authUser->can("$permission_name.edit")) {
                    $items[] = [
                        'permission' => "$permission_name.edit",
                        'title' => 'Ubah',
                        'icon' => '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'url' => route("$permission_name.edit", $primaryKey)
                    ];
                }

                if ($authUser->can("$permission_name.destroy")) {
                    $items[] = [
                        'permission' => "$permission_name.destroy",
                        'title' => 'Hapus',
                        'icon' => '<i class="ki-duotone ki-trash fs-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                        'class' => 'text-danger',
                        'attributes' => [
                            'onclick' => "forceDeleteDataDataTable('" . route("$permission_name.destroy", $primaryKey) . "')",
                        ]
                    ];
                }

                return view('components.button-dropdown', [
                    'items' => $items
                ])->render();
            })
            ->editColumn('id', fn($row) => encode($row->id))
            ->editColumn('status', function ($row) {
                $status = $this->articleRepository->getStatusOptions();
                return $status[$row->status];
            })
            ->editColumn('published_at', function ($row) {
                return tanggal($row->published_at, 'd F Y H:i:s') . ($row->published_at ? ' WIB' : '');
            })
            ->filterColumn('title', function ($query, $keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->filterColumn('author', function ($query, $keyword) {
                $query->whereHas('author', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function findById($id, $with = [])
    {
        return $this->articleRepository->findById($id, $with);
    }

    public function getStatusOptions()
    {
        return $this->articleRepository->getStatusOptions();
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $featured_image = null;
            if (isset($request['featured_image']) && is_file($request['featured_image'])) {
                $featured_image = $this->uploadFile($request['featured_image'], 'article');
            }

            $thumbnailPath = null;
            if (isset($request['thumbnail']) && is_file($request['thumbnail'])) {
                $thumbnailPath = $this->uploadFile($request['thumbnail'], 'article');
            }

            $content = $this->moveContentImages($request['content'], 'article');

            $slug = Str::slug($request['slug']);
            $read_time = calculateReadTime($content);

            $result = $this->articleRepository->create([
                'title' => $request['title'],
                'slug' => $slug,
                'meta_title' => $request['meta_title'],
                'meta_description' => $request['meta_description'],
                'content' => $content,
                'excerpt' => $request['excerpt'],
                'featured_image' => $featured_image,
                'thumbnail' => $thumbnailPath,
                'is_featured' => $request['is_featured'] ?? 0,
                'author' => auth()->user()->id,
                'status' => $request['status'],
                'published_at' => $request['published_at'],
                'read_time' => $read_time,
            ]);


            if (isset($request['categories']) && is_array($request['categories'])) {
                $categoryIds = $this->categoryRepository->getBaseQuery()
                    ->whereIn('slug', $request['categories'])
                    ->pluck('id');

                $result->categories()->sync($categoryIds);
            }

            if (isset($request['tags']) && is_array($request['tags'])) {
                $tagIds = $this->tagRepository->getBaseQuery()
                    ->whereIn('slug', $request['tags'])
                    ->pluck('id');

                $tagIds = [];

                foreach ($request['tags'] as $tagName) {
                    $tag = $this->tagRepository->firstOrCreate(['name' => $tagName, 'slug' => Str::slug($tagName)]);

                    $tagIds[] = $tag->id;
                }

                $result->tags()->sync($tagIds);
            }

            DB::commit();

            $this->activityCreate('Menambahkan artikel', $result);

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $article = $this->findById($id);
            if (!$article) {
                throw new AppException(DATA_TIDAK_DITEMUKAN);
            }

            $featured_image = $article->featured_image;
            if (isset($request['featured_image']) && is_file($request['featured_image'])) {
                if ($featured_image) {
                    $this->deleteFileByUrl($featured_image);
                }
                $featured_image = $this->uploadFile($request['featured_image'], 'article');
            }

            $thumbnailPath = $article->thumbnail;
            if (isset($request['thumbnail']) && is_file($request['thumbnail'])) {
                if ($thumbnailPath) {
                    $this->deleteFileByUrl($thumbnailPath);
                }
                $thumbnailPath = $this->uploadFile($request['thumbnail'], 'article');
            }

            $content = $this->moveContentImages($request['content'], 'article');
            $read_time = calculateReadTime($content);

            $slug = $request['slug'] ? Str::slug($request['slug']) : $article->slug;

            $article = $this->articleRepository->update($id, [
                'title' => $request['title'] ?? $article->title,
                'slug' => $slug,
                'meta_title' => $request['meta_title'] ?? $article->meta_title,
                'meta_description' => $request['meta_description'] ?? $article->meta_description,
                'content' => $content,
                'excerpt' => $request['excerpt'] ?? $article->excerpt,
                'featured_image' => $featured_image,
                'thumbnail' => $thumbnailPath,
                'is_featured' => $request['is_featured'] ?? $article->is_featured,
                'status' => $request['status'] ?? $article->status,
                'published_at' => $request['published_at'] ?? $article->published_at,
                'read_time' => $read_time,
            ]);

            if (isset($request['categories']) && is_array($request['categories'])) {
                $categoryIds = $this->categoryRepository->getBaseQuery()
                    ->whereIn('slug', $request['categories'])
                    ->pluck('id');
                $article->categories()->sync($categoryIds);
            }

            if (isset($request['tags']) && is_array($request['tags'])) {
                $tagIds = [];
                foreach ($request['tags'] as $tagName) {
                    $tag = $this->tagRepository->firstOrCreate([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName),
                    ]);
                    $tagIds[] = $tag->id;
                }
                $article->tags()->sync($tagIds);
            }

            DB::commit();

            $this->activityUpdate('Mengubah artikel', $article);

            return $article;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $result = $this->findById($id);
            if (!$result) throw new AppException(DATA_TIDAK_DITEMUKAN);

            $filesToDelete = $this->collectFiles(
                $result->featured_image,
                $result->thumbnail,
                $result->content
            );

            $this->articleRepository->delete($id);

            $this->activityDelete('Menghapus artikel', null, $result);

            foreach ($filesToDelete as $file) {
                $this->deleteFileByUrl($file);
            }

            DB::commit();

            return $result;
        } catch (AppException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function removeImage($id, $field)
    {
        $article = $this->findById($id);
        if (!$article) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        if (!empty($article->{$field})) {
            $this->deleteFile($article->{$field});
        }

        $article = $this->articleRepository->update($id, [$field => null]);

        return $article;
    }

    public function toogleFeatured($id)
    {
        $article = $this->findById($id);
        if (!$article) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        $article = $this->articleRepository->update($id, ['is_featured' => !$article->is_featured]);

        $this->activityUpdate('Mengubah data artikel', $article);

        return $article;
    }

    public function changeStatus($id, $status)
    {
        $article = $this->findById($id);
        if (!$article) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        $article = $this->articleRepository->update($id, ['status' => $status]);

        $this->activityUpdate('Mengubah data artikel', $article);

        return $article;
    }
}
