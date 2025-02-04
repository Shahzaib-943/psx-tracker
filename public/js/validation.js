$(document).ready(function () {
    $("#signupForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true,
            },
            role_id: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password",
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            email: "Please enter a valid email address",
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
            role_id: {
                required: "Please select a role",
            },
            password_confirmation: {
                required: "Please confirm your password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else if (
                element.prop("type") === "radio" &&
                element.parent(".radio-inline").length
            ) {
                error.insertAfter(element.parent().parent());
            } else if (
                element.prop("type") === "checkbox" ||
                element.prop("type") === "radio"
            ) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            }
        },
        unhighlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            }
        },
    });

    $("#editUserForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true,
            },
            role_id: {
                required: true,
            },
            password: {
                required: false,
                minlength: function () {
                    return $("#password").val().length > 0 ? 8 : 0;
                },
            },
            password_confirmation: {
                required: function () {
                    return $("#password").val().length > 0;
                },
                minlength: function () {
                    return $("#password").val().length > 0 ? 8 : 0;
                },
                equalTo: "#password",
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            email: "Please enter a valid email address",
            role_id: {
                required: "Please select a role",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
            password_confirmation: {
                required: "Please confirm your password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            }
        },
        unhighlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            }
        },
    });

    // Login Form

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address",
            },

            password: "Please provide a password",
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            }
        },
        unhighlight: function (element, errorClass) {
            if (
                $(element).prop("type") != "checkbox" &&
                $(element).prop("type") != "radio"
            ) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            }
        },
    });
    // Login Form

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address",
            },

            password: "Please provide a password",
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });

    // Password Reset Form

    $("#passwordResetForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });

    $("#createRoleForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });

    $("#createEventTypeForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            user_id: {
                required: false,
                digits: {
                    depends: function (element) {
                        return $(element).val().length > 0;
                    },
                },
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            user_id: {
                digits: "Please enter a valid integer value for user ID.",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });

    $("#editEventTypeForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            user_id: {
                required: false,
                digits: {
                    depends: function (element) {
                        return $(element).val().length > 0;
                    },
                },
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            user_id: {
                digits: "Please enter a valid integer value for user ID.",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });
    $("#createEventForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            type_id: {
                required: true,
            },
            date: {
                required: true,
            },
            reminderEnabled: {
                required: false, // It's a checkbox, not required unless checked
            },
            reminderTiming: {
                required: function () {
                    return $("#reminderCheckbox").is(":checked"); // Only required if reminder is enabled
                },
            },
            reminderCustomIntervalValue: {
                required: function () {
                    return (
                        $("#reminderTiming").val() === "custom_interval" &&
                        $("#reminderCheckbox").is(":checked")
                    );
                },
                digits: true, // Should be a number
                min: 1, // Minimum value for the interval (can't be zero or negative)
            },
            reminderCustomIntervalUnit: {
                required: function () {
                    return (
                        $("#reminderTiming").val() === "custom_interval" &&
                        $("#reminderCheckbox").is(":checked")
                    );
                },
            },
            reminderCustomDateValue: {
                required: function () {
                    return (
                        $("#reminderTiming").val() === "custom_date" &&
                        $("#reminderCheckbox").is(":checked")
                    );
                },
            },
            ackEnabled: {
                required: false, // It's a checkbox, not required unless checked
            },
            ackInterval: {
                required: function () {
                    return $("#ackCheckbox").is(":checked"); // Only required if acknowledgment is enabled
                },
            },
            ackCustomIntervalValue: {
                required: function () {
                    return (
                        $("#ackInterval").val() === "custom_interval" &&
                        $("#ackCheckbox").is(":checked")
                    );
                },
                digits: true, // Should be a number
                min: 1, // Minimum value for the interval (can't be zero or negative)
            },
            ackCustomIntervalUnit: {
                required: function () {
                    return (
                        $("#ackInterval").val() === "custom_interval" &&
                        $("#ackCheckbox").is(":checked")
                    );
                },
            },
            ackCustomDateValue: {
                required: function () {
                    return (
                        $("#ackInterval").val() === "custom_date" &&
                        $("#ackCheckbox").is(":checked")
                    );
                },
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            reminderTiming: {
                required: "Please select a reminder time",
            },
            reminderCustomIntervalValue: {
                required: "Please enter a custom interval value",
                digits: "Please enter a valid number for the interval",
                min: "The interval must be greater than zero",
            },
            reminderCustomIntervalUnit: {
                required: "Please select a unit for the custom interval",
            },
            reminderCustomDateValue: {
                required: "Please select a custom reminder date",
            },
            ackInterval: {
                required: "Please select an acknowledgment interval",
            },
            ackCustomIntervalValue: {
                required:
                    "Please enter a custom interval value for acknowledgment",
                digits: "Please enter a valid number for the acknowledgment interval",
                min: "The interval must be greater than zero",
            },
            ackCustomIntervalUnit: {
                required:
                    "Please select a unit for the custom acknowledgment interval",
            },
            ackCustomDateValue: {
                required: "Please select a custom acknowledgment date",
            },
        },

        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });
    const userRole = window.appConfig.userRole;
    const ADMIN = window.appConfig.ADMIN;
    $("#createFinanceCategoryForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            finance_type_id: {
                required: true,
                digits: true,
            },
            color: {
                required: true,
            },
            user_id: {
                required: function () {
                    return !$('#is_common').is(':checked');
                },
                digits: true,
            },
            is_common: {
                required: function () {
                    return !$('#user_id').val();
                },
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            finance_type_id: {
                required: "Please select a finance type",
                digits: "Must be a valid ID",
            },
            color: {
                required: "Please select a color",
            },
            user_id: {
                required: "Please select a user",
                digits: "Must be a valid ID",
            },
        },

        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });
    $("#editFinanceCategoryForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            finance_type_id: {
                required: true,
                digits: true,
            },
            color: {
                required: true,
            },
            user_id: {
                required: function () {
                    return !$('#is_common').is(':checked');
                },
                digits: true,
            },
            is_common: {
                required: function () {
                    return !$('#user_id').val();
                },
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            finance_type_id: {
                required: "Please select a finance type",
                digits: "Must be a valid ID",
            },
            color: {
                required: "Please select a color",
            },
            user_id: {
                required: "Please select a user",
                digits: "Must be a valid ID",
            },
        },

        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });
    $("#createFinanceRecordForm").validate({
        rules: {
            date: {
                required: true,
                dateISO: true,
            },
            finance_type_id: {
                required: true,
                digits: true,
            },
            finance_category_id: {
                required: true,
                digits: true,
            },
            amount: {
                required: true,
                number: true, // Allows decimals
                min: 0.1, // Ensures a minimum amount like 0.10
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Name must consist of at least 3 characters",
            },
            finance_type_id: {
                required: "Please select a finance type",
                digits: "Must be a valid ID",
            },
            finance_category_id: {
                required: "Please select a finance category",
                digits: "Must be a valid ID",
            },
            amount: {
                required: "Please enter amount",
                digits: "Must be a valid amount",
            },
        },

        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");

            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
    });
});
