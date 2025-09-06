$(function () {
    setCsrf();
    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    })
});

const setCsrf = (response) => {
    csrf = response?.csrf;
    if (csrf) {
        $('meta[name="csrf-token"]').attr("content", response.csrf);
    }
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
};

const initAjaxDataTable = (selector, options = {}) => {
    const defaultOrder = [];

    const defaultOptions = {
        responsive: true,
        searchDelay: 5000,
        processing: true,
        serverSide: true,
        order: options.order && options.order.length > 0 ? options.order : defaultOrder,
        columnDefs: [
            {
                responsivePriority: 1,
                targets: [0, -1],
            },
        ],
        lengthMenu: [
            [10, 20, 50, 100],
            [10, 20, 50, 100],
        ],
        pageLength: 10,

        initComplete: function (settings, json) {
            $("[data-kt-menu]").each(function () {
                var menu = new KTMenu(this);
            });
        },
        drawCallback: function (settings) {
            $("[data-kt-menu]").each(function () {
                new KTMenu(this);
            });
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        language: {
            emptyTable: `
                <div class="text-center px-4 py-10">
                    <i class="bi bi-inbox fs-5x text-muted"></i>
                    <div class="mt-2 text-muted">Belum ada data</div>
                </div>
            `
        }
    };

    const { order, ...restOptions } = options;

    const finalOptions = $.extend(true, {}, defaultOptions, restOptions);

    finalOptions.order = order && order.length > 0 ? order : defaultOrder;

    return $(selector).DataTable(finalOptions);
}

const setFilterDataTable = ($element, $table) => {
    for (let index = 0; index < $element.length; index++) {
        const element = $($element[index]);

        //jika element tidak ada skip
        if (element.length == 0) {
            continue;
        }

        const tagName = element[0].tagName.toLowerCase();

        if (tagName == "input") {
            const type = element.attr("type");
            if (type == "search") {
                // global search
                element.on("input", function () {
                    const tableInstance = $($table).DataTable();
                    const val = this.value;

                    tableInstance.search(val).draw();

                    tableInstance.off("preXhr.dt");
                });
            } else if (type == "text" || type == "tel" || type == "numeric") {
                element.on("keyup change", () => customSearchDataTable($table, $element));
            } else {
                element.on("change", () => customSearchDataTable($table, $element));
            }
        } else if (tagName == "select") {
            element.on("change", () => customSearchDataTable($table, $element));
        }
    }

    let tableSrip = $table.replace(/#|\]|\[|\./g, ""); //replace #.[]
    $(`.buttons-reset[aria-controls="${tableSrip}"]`).on("click", () =>
        customResetDataTable($table, $element)
    );
};

const customSearchDataTable = ($table, $element) => {
    $($table).off("preXhr.dt").on("preXhr.dt", function (e, settings, data) {
        for (let index = 0; index < $element.length; index++) {
            const element = $($element[index]);
            data[element.attr("name")] = element.val();
        }
    });
    $($table).DataTable().ajax.reload(null, true);
};

const customResetDataTable = ($table, $element = []) => {
    if ($element.length === 0) {
        $element = $(`[datatable-filter]`);
    }

    for (let index = 0; index < $element.length; index++) {
        const el = $($element[index]);
        const tagName = el.prop("tagName").toLowerCase();
        const type = el.attr("type");

        if (tagName === "select") {
            el.val("").trigger("change.select2").trigger("change");
        } else if (tagName === "input") {
            el.val("");
            if (type === "search") {
                el.trigger("input");
            } else {
                el.trigger("change");
            }
        } else {
            el.val("").trigger("change");
        }
    }

    $($table).DataTable().search("").draw();

    customSearchDataTable($table, $element);
};

const konfirmasiSweet = (
    message,
    confirmMessage = "Ya",
    cancelMessage = "Tidak",
    icon = "info"
) => {
    return new Promise((resolve, reject) => {
        Swal.fire({
            html: message,
            icon: icon,
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: confirmMessage,
            cancelButtonText: cancelMessage,
            reverseButtons: true,
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-secondary",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                resolve({ confirmed: true });
            } else {
                reject(false);
            }
        });
    });
};

const alertSweet = (text, icon = "success") => {
    if (icon === true) {
        icon = "success";
    } else if (icon === false) {
        icon = "error";
    }

    return Swal.fire({
        text: text,
        icon: icon,
        buttonsStyling: false,
        confirmButtonText: "Oke!",
        customClass: {
            confirmButton: "btn btn-primary",
        },
    });
};

const ajaxMaster = (
    url,
    method,
    data = {},
    beforeSend,
    withLoadingScreen = true
) => {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            data: data,
            beforeSend: () => {
                if (withLoadingScreen) loaderPage(true);
                if (typeof beforeSend === "function") beforeSend();
            },
            success: function (response) {
                if (withLoadingScreen) loaderPage(false);

                setCsrf(response);
                resolve(response);
            },
            error: function (err) {
                loaderPage(false);
                reject(err);
            },
        });
    });
};

const loaderPage = (load = false) => {
    if (load === true) {
        $(
            `<div loader_body>
                <div class="loader-overlay">
                    <div class="spinner-border text-light" role="status"></div>
                    <span class="loader-text">Mohon Tunggu...</span>
                </div>
            </div>`
        )
            .appendTo(document.body)
            .hide()
            .fadeIn(150);
    } else {
        $("[loader_body]").fadeOut(150, function () {
            $(this).remove();
        });
    }
};

const globalHapusData = (
    src,
    sendData = {},
    redirectLocation = false,
    typeDelete = ""
) => {
    if (!src) {
        toastr.error("Alamat src tujuan tidak valid!", "Terjadi kesalahan");
        return false;
    }

    let message = `Yakin hapus data, data akan dihapus dari sistem!`;

    if (typeDelete == "force") {
        message = `Yakin hapus data, data akan dihapus permanen dari sistem!`;
    }

    return new Promise((resolve, reject) => {
        konfirmasiSweet(message, "Ya, hapus!", "Tidak, batalkan", "warning").then(() => {
            ajaxMaster(src, "DELETE", sendData, false)
                .then((ress) => {
                    alertSweet(
                        ress.message,
                        ress.success ? "success" : "error"
                    ).then(() => {
                        if (redirectLocation) {
                            document.location.href = redirectLocation;
                        }
                        resolve(ress);
                    });
                })
                .catch((error) => {
                    handleError(error);
                    reject(error);
                });
        }).catch(() => { });
    });
};

const deleteDataDataTable = (url, sendData = {}, typeDelete = "soft") => {
    return new Promise((resolve, reject) => {
        globalHapusData(url, sendData, false, typeDelete)
            .then((success) => {
                reloadDataTable().then(() => {
                    resolve(success);
                });
            })
            .catch((error) => {
                reject(error);
            });
    });
};

function forceDeleteDataDataTable(url, sendData = {}) {
    return new Promise((resolve, reject) => {
        globalHapusData(url, sendData, false, "force")
            .then((success) => {
                reloadDataTable().then(() => resolve(success));
            })
            .catch((error) => {
                reject(error);
            });
    });
}

const reloadDataTable = (table = DATATABLE_ID) => {
    return new Promise((resolve, reject) => {
        try {
            $(`#${table}`).DataTable().ajax.reload(() => {
                resolve();
            }, false);
        } catch (error) {
            reject(error);
        }
    });
};

const handleError = (err) => {
    if (err.status == 422) {
        toastr.error("Periksa kembali form yang bertanda merah!", err.status);
        return false;
    }

    if (err.status == 404) {
        toastr.error("Data tidak ditemukan!", err.status);
        return false;
    }

    if (err.responseJSON.message) {
        toastr.error(err.responseJSON.message, err.status);
        return false;
    }
};

const actionModalData = (btn, titleButtonDone = "Simpan") => {
    let btnModal = $(btn);
    let buttonDone = false;

    if (btnModal.attr("btndetail") === undefined) {
        buttonDone = {
            title: titleButtonDone,
            autoClose: false,
            action: function () {
                $("#default-ikh-modal").find("form").submit();
            },
        };
    }

    openModalLG({
        title: btnModal.data("title"),
        src: btnModal.data("url"),
        buttonClose: {
            title: "Tutup",
            action: function () { },
        },
        buttonDone: buttonDone,
    });
};


const openModalLG = (param) => {
    openModal(param, "modal-lg");
};

const openModal = (param, msize = "modal-lg") => {
    if (!param.src) {
        toastr.error("Alamat src tujuan tidak valid!", "Terjadi kesalahan");
        return false;
    }

    const modalDom = param.modalDom ?? "#default-ikh-modal";
    let modal = $(modalDom),
        buttonDone = modal.find("[btnModalDone]"),
        buttonClose = modal.find("[btnModalClose]");

    // set size modal
    modal.find("#modal-dialog").removeClass().addClass(`modal-dialog ${msize}`);

    // init modal
    IKHMODAL = new bootstrap.Modal(modal);

    param.buttonDone === false ? buttonDone.hide() : buttonDone.show();

    loaderPage(true);

    $.get(param.src)
        .done(function (out) {
            modal.find(".modal-title").html(param.title);
            modal.find("[btnModalClose]").html(param.buttonClose.title);
            modal.find("[btnModalDone]").html(param.buttonDone.title);
            modal.find(".modal-body").html(out);
            IKHMODAL.show(); //show

            if (typeof initModalImageInputs === 'function') {
                initModalImageInputs(modal);
            }

            buttonClose.unbind("click").click(function () {
                if (typeof param.buttonClose != "undefined") {
                    if (typeof param.buttonClose.action != "undefined") {
                        param.buttonClose.action();
                    }
                }

                modal.find(".modal-title").empty();
                modal.find("[btnModalClose]").empty();
                modal.find("[btnModalDone]").empty();
                modal.find(".modal-body").empty();

                $(this).unbind("click");
            });

            if (param.buttonDone !== false) {
                buttonDone.unbind("click").click(function () {
                    param.buttonDone.action(modal);
                    if (param.buttonDone.autoClose == false) {
                    } else {
                        buttonClose.click();
                        $(this).unbind("click");
                    }
                });
            }
        })
        .always(function () {
            loaderPage(false);
        })
        .fail(function (err) {
            handleError(err);
        });
};

const submitModalDataTable = (form) => {
    globalSimpanMaster(form, $(form).attr("action"), "POST")
        .then((ress) => {
            if (typeof IKHMODAL == "object") IKHMODAL.hide();
            reloadDataTable();
        })
        .catch((err) => {
            setInvalidFeedBack(err, form);
        });
};

const globalSimpanMaster = (form, url, method = "POST") => {
    loaderPage(true);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: method,
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message, "Sukses..");
                } else {
                    toastr.error(response.message, "Opps..");
                }

                resolve(response);
            },
            error: function (err) {
                handleError(err);
                reject(err);
            },
        })
            .done(function (z) {
                return false;
            })
            .always(function () {
                loaderPage(false);
            })
    })
};

const cleanInvalidFeedBack = (form) => {
    $(form).find("input,select").removeClass("is-invalid");
    $(form).find(".invalid-message").remove();
};

const setInvalidFeedBack = (error, form, nameArrayKeyCustom = false) => {
    let errs = error.responseJSON?.errors;
    let splitName = "."; //
    let formInputan;

    cleanInvalidFeedBack(form);
    if (errs) {
        for (let [inputName, errorMessage] of Object.entries(errs)) {
            //jika name menggunakan key yang custom  semisal nama[0], nama[2], nama[5]
            if (nameArrayKeyCustom) {
                let splitArr = inputName.split(splitName);
                inputName = "";
                for (let index = 0; index < splitArr.length; index++) {
                    if (index == 0) inputName += splitArr[index];
                    else inputName += `[${splitArr[index]}]`;
                }

                formInputan = $(`[name^="${inputName}"]`);
            } else {
                // jika key tedapat ".", maka name berbentuk array, semisal: nama[]
                let keyInputan = 0;
                if (inputName.includes(splitName)) {
                    let splitArr = inputName.split(splitName);
                    keyInputan = splitArr[splitArr.length - 1];
                    splitArr.splice(splitArr.indexOf(keyInputan), 1); // remove last Value
                    inputName = splitArr[0];
                }

                formInputan = $(`[name^="${inputName}"]`);

                if (formInputan.length > 1) {
                    formInputan = $(formInputan[keyInputan]); //reinit
                }
            }

            if (formInputan.hasClass("image-input")) {
                formInputan
                    .closest(".image-input-pembungkus")
                    .find(".image-input-messsage")
                    .append(
                        `<div class="text-danger invalid-message">${errorMessage}</div>`
                    );
            } else {
                formInputan
                    .addClass("is-invalid")
                    .closest('.fv-row')
                    .append(
                        `<div class="text-danger invalid-message">${errorMessage}</div>`
                    );
            }
        }
    }

    return errs;
};


// component form image
window.initModalImageInputs = function (modal) {
    const $modal = $(modal);

    $modal.find('[data-kt-image-input="true"]').each(function () {
        const el = this;
        let instance = KTImageInput.getInstance(el);
        if (!instance) {
            instance = new KTImageInput(el);
        }

        if (!el.dataset.defaultImage) {
            const bg = $(el).find('.image-input-wrapper').css('background-image');
            el.dataset.defaultImage = bg && bg !== 'none' ? bg : '';
        }

        $(el).find('input[type="file"]').off('change.preview').on('change.preview', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                const url = `url(${ev.target.result})`;
                $(el).find('.image-input-wrapper').css({
                    'background-image': url,
                    'background-size': 'cover',
                    'background-repeat': 'no-repeat'
                });
            };
            reader.readAsDataURL(file);
        });

        instance.on("kt.imageinput.canceled", function () {
            const defaultImage = el.dataset.defaultImage || '';
            $(el).find('.image-input-wrapper').css('background-image', defaultImage);
            $(el).removeClass('image-input-changed').addClass('image-input-empty');
            $(el).find('input[type="file"]').val('');
        });
    });
};
