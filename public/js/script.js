function confirmDeletion(url, itemType, callback) {
    Swal.fire({
        title: "Are you sure?",
        text: `You won't be able to revert this ${itemType}!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire(
                        "Deleted!",
                        `${itemType} has been deleted.`,
                        "success"
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error!",
                        `An error occurred while deleting the ${itemType}.`,
                        "error"
                    );
                },
            });
        }
    });
}

$(document).on("click", ".delete-button", function () {
    var url = $(this).data("url");
    var itemType = $(this).data("type");
    confirmDeletion(url, itemType);
});
