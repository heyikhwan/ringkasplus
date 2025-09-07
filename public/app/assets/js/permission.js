$(document).ready(function () {
    function updateGroup($card) {
        let $children = $card.find(".form-check-input[data-role=child]");
        let checkedCount = $children.filter(":checked").length;
        let total = $children.length;

        $card.find("[data-count=checked]").text(checkedCount);
        $card.find("[data-count=total]").text(total);

        let $checkAll = $card.find(".form-check-input[data-role=all]");
        $checkAll.prop("checked", checkedCount === total && total > 0);

        let $badge = $card.find(".badge");
        if (checkedCount === total && total > 0) {
            $badge.removeClass("badge-light-secondary").addClass("badge-light-primary");
        } else {
            $badge.removeClass("badge-light-primary").addClass("badge-light-secondary");
        }
    }

    function updateGlobalCheckAll() {
        let allGroups = $(".permission-card");
        let allChecked = true;

        allGroups.each(function () {
            let $children = $(this).find(".form-check-input[data-role=child]");
            if ($children.length && $children.filter(":checked").length !== $children.length) {
                allChecked = false;
                return false;
            }
        });

        $("#check-all-global").prop("checked", allChecked);
    }

    // check-all per group
    $(document).on("change", ".form-check-input[data-role=all]", function () {
        let $card = $(this).closest(".permission-card");
        let isChecked = $(this).is(":checked");
        $card.find(".form-check-input[data-role=child]").prop("checked", isChecked);
        updateGroup($card);
        updateGlobalCheckAll();
    });

    // child checkbox
    $(document).on("change", ".form-check-input[data-role=child]", function () {
        let $card = $(this).closest(".permission-card");
        updateGroup($card);
        updateGlobalCheckAll();
    });

    // global check-all
    $(document).on("change", "#check-all-global", function () {
        let isChecked = $(this).is(":checked");
        $(".permission-card").each(function () {
            $(this).find(".form-check-input[data-role=child]").prop("checked", isChecked);
            updateGroup($(this));
        });
    });

    $(".permission-card").each(function () {
        updateGroup($(this));
    });
    updateGlobalCheckAll();
});