<script>
    var hostUrl = "/app/assets/";
</script>
<script src="{{ asset('app/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('app/assets/js/scripts.bundle.js') }}"></script>
<script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }
        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }
        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
</script>
<script>
    function handleTogglePassword(toggle) {
        const wrapper = toggle.closest('.position-relative');
        const input = wrapper?.querySelector('input[type="password"], input[type="text"]');
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        const eye = toggle.querySelector('.ki-eye');
        const eyeSlash = toggle.querySelector('.ki-eye-slash');
        if (eye && eyeSlash) {
            eye.classList.toggle('d-none', !isPassword);
            eyeSlash.classList.toggle('d-none', isPassword);
        }
    }

    document.querySelectorAll('[data-kt-password-meter-control="visibility"]').forEach(toggle => {
        toggle.addEventListener('click', () => handleTogglePassword(toggle));
    });

    document.body.addEventListener('click', function(e) {
        const toggle = e.target.closest('[data-kt-password-meter-control="visibility"]');
        if (toggle) handleTogglePassword(toggle);
    });
</script>
<script>
    function loadSelect2PerPage(selector, options = {}) {
        const defaults = {
            url: null,
            key: "id",
            value: "text",
            objectValue: null,
            placeholder: null,
            hashId: false,
            disableSearch: false,
            allowClear: false,
            closeOnSelect: true,
            tags: false,
            templateFormat: null,
            templateSelection: null,
            dropdownParent: null,
        };

        const opts = Object.assign({}, defaults, options);

        const mapResult = opts.objectValue ?? function(obj) {
            return {
                id: obj[opts.key],
                text: opts.value != null ? obj[opts.value] : obj[opts.key],
                ...obj,
            };
        };

        const select2Options = {
            ajax: {
                url: opts.url,
                dataType: "json",
                delay: 250,
                data: params => ({
                    q: params.term,
                    page: params.page,
                    hash_id: opts.hashId ? opts.key : null,
                }),
                processResults: (data, params) => ({
                    results: $.map(data.data, mapResult),
                    pagination: {
                        more: ((params.page || 1) * data.per_page) < data.total,
                    },
                }),
                cache: false,
            },
            placeholder: opts.placeholder,
            dropdownParent: opts.dropdownParent ?
                $(opts.dropdownParent) :
                $(selector).parent(),
            minimumResultsForSearch: opts.disableSearch ? -1 : 1,
            allowClear: opts.allowClear,
            tags: opts.tags,
            closeOnSelect: opts.closeOnSelect,
        };

        if (opts.templateFormat) {
            select2Options.templateResult = opts.templateFormat;
        }
        if (opts.templateSelection) {
            select2Options.templateSelection = opts.templateSelection;
        }

        $(selector).select2(select2Options);
    }
</script>
