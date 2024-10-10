<script>
    $('.delete_swal').on('click', function () {
        let title = $(this).data('title') ?? 'Are you sure?';
        let text = $(this).data('text') ?? 'This action cannot be undone!';
        let confirm_text = $(this).data('confirm_text') ?? 'Yes, confirm!';
        let icon = $(this).data('icon') ?? 'warning';
        let form_id = $(this).data('target_form_id')
        let target_form_id = $(this).parent('form');
        if (form_id) {
            target_form_id = $(form_id)
        }
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirm_text
        }).then((result) => {
            if (result.isConfirmed) {
                target_form_id.submit();
            }
        });
    })
</script>
