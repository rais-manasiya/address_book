$(document).ready(function() {
  // Initialize DataTable
  var table = $("#contactsTable").DataTable();

  $.validator.addMethod(
    "strictEmail",
    function (value, element) {
      return (
        this.optional(element) || /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(value)
      );
    },
    "Please enter a valid email address"
  );

  // Form validation rules
  var validationRules = {
    name: {
      required: true,
      minlength: 2,
    },
    first_name: {
      required: true,
      minlength: 2,
    },
    email: {
      required: true,
      strictEmail: true,
    },
    street: {
      required: true,
    },
    zip_code: {
      required: true,
      digits: true,
      minlength: 5,
      maxlength: 10,
    },
    city_id: {
      required: true,
    },
  };

  // Initialize form validation for add contact form
  $("#addContactForm").validate({
    rules: validationRules,
    messages: {
      name: {
        required: "Please enter a last name",
        minlength: "Last name must be at least 2 characters long",
      },
      first_name: {
        required: "Please enter a first name",
        minlength: "First name must be at least 2 characters long",
      },
      email: {
        required: "Please enter an email address",
        strictEmail: "Please enter a valid email address with a proper domain",
      },
      street: {
        required: "Please enter a street address",
      },
      zip_code: {
        required: "Please enter a zip code",
        digits: "Zip code must contain only digits",
        minlength: "Zip code must be at least 5 digits long",
        maxlength: "Zip code must not exceed 10 digits",
      },
      city_id: {
        required: "Please select a city",
      },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      // Add Contact
      $("#saveContact").click(function () {
        $.ajax({
          url: "controllers/ajax_handler.php",
          method: "POST",
          data: $("#addContactForm").serialize() + "&action=add",
          success: function (response) {
            var data = JSON.parse(response);
            if (data.status === "success") {
              $("#addContactModal").modal("hide");
              location.reload();
            } else {
              alert("Error: " + data.message);
            }
          },
        });
      });
    },
  });

  // Add Contact
  $("#saveContact").click(function () {
    $("#addContactForm").submit();
  });

  // Initialize form validation for edit contact form
  $("#editContactForm").validate({
    rules: validationRules,
    messages: {
      name: {
        required: "Please enter a last name",
        minlength: "Last name must be at least 2 characters long",
      },
      first_name: {
        required: "Please enter a first name",
        minlength: "First name must be at least 2 characters long",
      },
      email: {
        required: "Please enter an email address",
        strictEmail: "Please enter a valid email address with a proper domain",
      },
      street: {
        required: "Please enter a street address",
      },
      zip_code: {
        required: "Please enter a zip code",
        digits: "Zip code must contain only digits",
        minlength: "Zip code must be at least 5 digits long",
        maxlength: "Zip code must not exceed 10 digits",
      },
      city_id: {
        required: "Please select a city",
      },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: $("#editContactForm").serialize() + "&action=update",
        success: function (response) {
          var data = JSON.parse(response);
          if (data.status === "success") {
            $("#editContactModal").modal("hide");
            location.reload();
          } else {
            alert("Error: " + data.message);
          }
        },
      });
    },
  });

  // Edit Contact
  $(".edit-contact").click(function () {
    var id = $(this).data("id");
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "GET",
      data: { action: "get", id: id },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          $("#edit_id").val(data.contact.id);
          $("#edit_name").val(data.contact.name);
          $("#edit_first_name").val(data.contact.first_name);
          $("#edit_email").val(data.contact.email);
          $("#edit_street").val(data.contact.street);
          $("#edit_zip_code").val(data.contact.zip_code);
          $("#edit_city_id").val(data.contact.city_id);
          $("#editContactModal").modal("show");
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  // Update Contact
  $("#updateContact").click(function () {
    $("#editContactForm").submit();
  });

  // Delete Contact
  $(".delete-contact").click(function () {
    if (confirm("Are you sure you want to delete this contact?")) {
      var id = $(this).data("id");
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: { action: "delete", id: id },
        success: function (response) {
          var data = JSON.parse(response);
          if (data.status === "success") {
            location.reload();
          } else {
            alert("Error: " + data.message);
          }
        },
      });
    }
  });

  // Export XML
  $("#exportXML").click(function () {
    window.location.href = "controllers/export.php?format=xml";
  });

  // Export JSON
  $("#exportJSON").click(function () {
    window.location.href = "controllers/export.php?format=json";
  });
});