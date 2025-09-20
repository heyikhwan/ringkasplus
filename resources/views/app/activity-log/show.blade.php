  <x-app-layout title="Detail {!! $title !!}">
      <div class="row g-5">
          <div class="col-12">
              <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title mb-0"><i class="fas fa-info-circle text-primary me-2"></i> Informasi
                          {{ $title }}</h5>

                      <div>
                          <a href="{{ route($permission_name . '.index') }}" class="btn btn-light">
                              <i class="fas fa-arrow-left me-1"></i> Kembali
                          </a>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="row g-8">
                          <div class="col-md-4">
                              <h6><i class="fas fa-bolt text-warning me-1"></i> Event</h6>
                              <p class="m-0">
                                  <span
                                      class="badge 
                                @switch(Str::title($result->event))
                                    @case('Created') badge-success @break
                                    @case('Updated') badge-warning @break
                                    @case('Deleted') badge-danger @break
                                    @case('Login') badge-primary @break
                                    @case('Logout') badge-secondary @break
                                    @case('Generate Massal') badge-info @break
                                    @case('Importer') badge-info @break
                                    @default badge-light text-dark
                                @endswitch">
                                      <i class="fas fa-circle me-1"></i>
                                      {{ Str::title(str_replace('_', ' ', $result->event)) }}
                                  </span>
                              </p>
                          </div>

                          <div class="col-md-4">
                              <h6><i class="fas fa-layer-group text-info me-1"></i> Kategori</h6>
                              <p class="m-0 text-capitalize">{{ $result->log_name }}</p>
                          </div>

                          <div class="col-md-4">
                              <h6><i class="fas fa-align-left text-secondary me-1"></i> Deskripsi</h6>
                              <p class="m-0">{{ $result->description }}</p>
                          </div>

                          <div class="col-md-4">
                              <h6><i class="fas fa-user text-primary me-1"></i> User</h6>
                              <p class="m-0">{{ $result->causer->name }}</p>
                          </div>

                          <div class="col-md-4">
                              <h6><i class="fas fa-clock text-danger me-1"></i> Waktu</h6>
                              <p class="m-0">{{ tanggal($result->created_at, 'l, d F Y H:i:s') }} WIB</p>
                          </div>

                          <div class="col-md-4">
                              <h6><i class="fas fa-random text-dark me-1"></i> Batch UUID</h6>
                              <p class="m-0">{{ !empty($result->batch_uuid) ? $result->batch_uuid : '-' }}</p>
                          </div>

                          @if (count($result->properties) > 0)
                              <div class="col-12">
                                  <hr class="text-muted">

                                  <h5 class="mt-4"><i class="fas fa-code text-success me-1"></i> Properties</h5>
                                  <div class="rounded bg-light p-3" style="max-height: 300px; overflow:auto;">
                                      <pre class="m-0 text-dark"><code>{{ json_encode($result->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                  </div>
                              </div>
                          @endif
                      </div>
                  </div>
              </div>
          </div>

          @if ($detail->isNotEmpty())
              <div class="col-12">
                  <div class="card">
                      <div class="card-header">
                          <h5 class="card-title mb-0"><i class="fas fa-list text-primary me-2"></i> Detail
                              {{ $title }}</h5>
                      </div>
                      <div class="card-body">
                          <div class="d-grid gap-4">
                              @foreach ($detail as $item)
                                  <div class="border rounded p-4 shadow-sm">
                                      <div class="d-flex align-items-start gap-5">
                                          <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold"
                                              style="width: 40px; height: 40px">
                                              {{ $loop->iteration }}
                                          </div>

                                          <div class="flex-grow-1">
                                              <h6 class="mb-1">{{ $item->description }}</h6>
                                              <div class="d-flex gap-3 text-muted small mb-4">
                                                  <span><i
                                                          class="fas fa-bolt me-1"></i>{{ str_replace('_', ' ', Str::title($item->event)) }}</span>
                                                  <span><i
                                                          class="fas fa-clock me-1"></i>{{ tanggal($item->created_at, 'l, d F Y H:i:s') }}</span>
                                              </div>

                                              <div class="rounded bg-light p-3">
                                                  <pre class="m-0 text-dark small"><code>{{ json_encode($item->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          </div>
                      </div>
                  </div>
              </div>
          @endif
      </div>
  </x-app-layout>
