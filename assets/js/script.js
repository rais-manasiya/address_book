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
  });

  // Initialize form validation for edit contact form
  $("#editContactForm").validate({
    rules: validationRules,
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
  });

  // Contact CRUD operations
  $("#saveContact").click(function () {
    if ($("#addContactForm").valid()) {
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: $("#addContactForm").serialize() + "&action=add_contact",
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
    }
  });

  // Edit Contact
  $(".edit-contact").click(function () {
    var id = $(this).data("id");
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "GET",
      data: { action: "get_contact", id: id },
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
          $("#edit_tags").val(data.contact.tags);
          $("#editContactModal").modal("show");
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  // Update Contact
  $("#updateContact").click(function () {
    if ($("#editContactForm").valid()) {
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: $("#editContactForm").serialize() + "&action=update_contact",
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
    }
  });

  $(".delete-contact").click(function () {
    if (confirm("Are you sure you want to delete this contact?")) {
      var id = $(this).data("id");
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: { action: "delete_contact", id: id },
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

  // Group CRUD operations
  $("#saveGroup").click(function () {
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: $("#addGroupForm").serialize() + "&action=add_group",
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          $("#addGroupModal").modal("hide");
          location.reload();
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $(".edit-group").click(function () {
    var id = $(this).data("id");
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "GET",
      data: { action: "get_group", id: id },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          $("#edit_group_id").val(data.group.id);
          $("#edit_group_name").val(data.group.name);
          $("#editGroupModal").modal("show");
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $("#updateGroup").click(function () {
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: $("#editGroupForm").serialize() + "&action=update_group",
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          $("#editGroupModal").modal("hide");
          location.reload();
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $(".delete-group").click(function () {
    if (confirm("Are you sure you want to delete this group?")) {
      var id = $(this).data("id");
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "POST",
        data: { action: "delete_group", id: id },
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

  // Manage Group
  $(".manage-group").click(function () {
    var groupId = $(this).data("id");
    loadGroupContacts(groupId);
    loadConnectedGroups(groupId);
    $("#manageGroupModal").modal("show");
    $("#manageGroupModal").data("group-id", groupId);
  });

  function loadGroupContacts(groupId) {
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "GET",
      data: { action: "get_group_contacts", id: groupId },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          var html = "";
          data.contacts.forEach(function (contact) {
            html += "<tr>";
            html += "<td>" + contact.name + " " + contact.first_name + "</td>";
            html += "<td>" + contact.email + "</td>";
            html +=
              "<td>" + (contact.is_inherited == 1 ? "Yes" : "No") + "</td>";
            html += "<td>";
            if (contact.is_inherited == 0) {
              html +=
                '<button class="btn btn-sm btn-danger remove-contact-from-group" data-id="' +
                contact.id +
                '">Remove</button>';
            }
            html += "</td>";
            html += "</tr>";
          });
          $("#groupContactsBody").html(html);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  }

  function loadConnectedGroups(groupId) {
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "GET",
      data: { action: "get_connected_groups", id: groupId },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          var html = "";
          data.groups.forEach(function (group) {
            html += "<tr>";
            html += "<td>" + group.name + "</td>";
            html +=
              '<td><button class="btn btn-sm btn-danger disconnect-group" data-id="' +
              group.id +
              '">Disconnect</button></td>';
            html += "</tr>";
          });
          $("#connectedGroupsBody").html(html);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  }

  $("#addContactToGroup").click(function () {
    var groupId = $("#manageGroupModal").data("group-id");
    var contactId = $("#contactToAdd").val();
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: {
        action: "add_contact_to_group",
        group_id: groupId,
        contact_id: contactId,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          loadGroupContacts(groupId);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $("#connectGroup").click(function () {
    var parentGroupId = $("#manageGroupModal").data("group-id");
    var childGroupId = $("#groupToConnect").val();
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: {
        action: "connect_groups",
        parent_group_id: parentGroupId,
        child_group_id: childGroupId,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          loadConnectedGroups(parentGroupId);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $(document).on("click", ".remove-contact-from-group", function () {
    var groupId = $("#manageGroupModal").data("group-id");
    var contactId = $(this).data("id");
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: {
        action: "remove_contact_from_group",
        group_id: groupId,
        contact_id: contactId,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          loadGroupContacts(groupId);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  $(document).on("click", ".disconnect-group", function () {
    var parentGroupId = $("#manageGroupModal").data("group-id");
    var childGroupId = $(this).data("id");
    $.ajax({
      url: "controllers/ajax_handler.php",
      method: "POST",
      data: {
        action: "disconnect_groups",
        parent_group_id: parentGroupId,
        child_group_id: childGroupId,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.status === "success") {
          loadConnectedGroups(parentGroupId);
        } else {
          alert("Error: " + data.message);
        }
      },
    });
  });

  // Tag filtering
  $("#tagFilter").change(function () {
    var selectedTag = $(this).val();
    if (selectedTag) {
      $.ajax({
        url: "controllers/ajax_handler.php",
        method: "GET",
        data: { action: "get_contacts_by_tag", tag: selectedTag },
        success: function (response) {
          var data = JSON.parse(response);
          if (data.status === "success") {
            contactsTable.clear();
            data.contacts.forEach(function (contact) {
              contactsTable.row.add([
                contact.name,
                contact.first_name,
                contact.email,
                contact.street,
                contact.zip_code,
                contact.city_name,
                contact.tags,
                '<button class="btn btn-sm btn-info edit-contact" data-id="' +
                  contact.id +
                  '">Edit</button> ' +
                  '<button class="btn btn-sm btn-danger delete-contact" data-id="' +
                  contact.id +
                  '">Delete</button>',
              ]);
            });
            contactsTable.draw();
          } else {
            alert("Error: " + data.message);
          }
        },
      });
    } else {
      location.reload();
    }
  });

  // Export functions
  $("#exportXML").click(function () {
    window.location.href = "controllers/export.php?format=xml";
  });

  $("#exportJSON").click(function () {
    window.location.href = "controllers/export.php?format=json";
  });
});