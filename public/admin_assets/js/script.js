const resetFilters = () => {
    let url = new URL(window.location.href)
    url.search = '';
    window.location.href = url.toString()
}

$('#reset_fields').on('click', function () {
    resetFilters()
})


$('#per_page_input').on('change', function () {
    let per_page = $(this).val()
    $('input[name="per_page"]').val(per_page)
    $('#search_form').submit()
})


const handlePerPageOnLoad = () => {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('per_page')) {
        let perPageValue = urlParams.get('per_page');
        $('#per_page_input').val(perPageValue)
    }
}
handlePerPageOnLoad()
// $('#per_page_input')

$('.all-permission-select-checkbox').on('change', function () {
    let effected_classes = $(this).attr('data-class')
    if ($(this).is(':checked')) {
        $(`.${effected_classes}`).prop('checked', true)
    } else {
        $(`.${effected_classes}`).prop('checked', false)
    }
})


//permission checkbox checked

const handleAllPermissionCheckBox = () => {
    $('.all-permission-select-checkbox').each(function (index, node) {
        let checked = true
        $(this).each(function (ind, checkbox) {
            let data_class = $(this).attr('data-class')

            $(`.${data_class}`).each(function (indx, item) {
                let is_checked = $(this).is(':checked')
                if (!is_checked) {
                    checked = false
                }
            })
        })
        $(this).prop('checked', checked)
    })
}

handleAllPermissionCheckBox()
$('.permission_checkbox').on('change', function () {
    handleAllPermissionCheckBox()
})

const formatSlug = (str) => {
    return str
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
}

$('#name').on('input', function () {
    $('#slug').val(formatSlug($(this).val()))
})

$('.fa-camera').on('click', function () {
    $(this).siblings('.image-upload-input').trigger('click')
})
$('.image-upload-input').on('change', function () {
    let image = URL.createObjectURL(this.files[0])
    $(this).siblings('.image-preview').attr('src', image)
})
