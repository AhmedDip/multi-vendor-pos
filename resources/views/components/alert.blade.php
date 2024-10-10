@push('script')
    <script>
        Swal.fire({
            position: "top-end",
            icon: "{{$class}}",
            title: "{{$message}}",
            showConfirmButton: false,
            timer: 1500,
            toast: true
        });
    </script>
@endpush
