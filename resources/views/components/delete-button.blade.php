{{html()->form('delete', $route)->open()}}

<button
    type="button"
    class="delete_swal btn btn-sm btn-danger ms-1"
    data-title="Are sure to delete?"
    data-text="This action cannot be undone!"
    data-button_text="Yes, Delete!"
    >
    <i class="fa-solid fa-trash-can"></i>
</button>


{{ html()->form()->close() }}

