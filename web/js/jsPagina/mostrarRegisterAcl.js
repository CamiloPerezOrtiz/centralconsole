function mostrar(id) {
    if (id == "ip") {
        $("#ip").show();
        $("#subnet").hide();
        $("#ipRange").hide();
        $("#domain").hide();
        $("#username").hide();
        $("#ldapSearch").hide();
    }
    if (id == "subnet") {
        $("#ip").hide();
        $("#subnet").show();
        $("#ipRange").hide();
        $("#domain").hide();
        $("#username").hide();
        $("#ldapSearch").hide();
    }
    if (id == "ipRange") {
        $("#ip").hide();
        $("#subnet").hide();
        $("#ipRange").show();
        $("#domain").hide();
        $("#username").hide();
        $("#ldapSearch").hide();
    }
    if (id == "domain") {
        $("#ip").hide();
        $("#subnet").hide();
        $("#ipRange").hide();
        $("#domain").show();
        $("#username").hide();
        $("#ldapSearch").hide();
    }
    if (id == "username") {
        $("#ip").hide();
        $("#subnet").hide();
        $("#ipRange").hide();
        $("#domain").hide();
        $("#username").show();
        $("#ldapSearch").hide();
    }
    if (id == "ldapSearch") {
        $("#ip").hide();
        $("#subnet").hide();
        $("#ipRange").hide();
        $("#domain").hide();
        $("#username").hide();
        $("#ldapSearch").show();
    }
}
$( function() {
    $("#status").change( function() {
        if ($(this).val() === "1") {
            $("#id_input").prop("disabled", true);
        } else {
            $("#id_input").prop("disabled", false);
        }
    });
});