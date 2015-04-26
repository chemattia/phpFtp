/**
 * @version     js/mailupper.js 2015-04-23 07:47:00 UTC zanardi
 * @author      GiBiLogic <info@gibilogic.com>
 * @copyright   Copyright 2015 S-D Consulting http://www.iboxmail.it/
 */

// Short notation for DOM ready
jQuery("document").ready(function () {
    muCheckStatus($);
});

// Process all the product forms
muCheckStatus = function ($)
{
    $('#welcome span.result').html('OK');
    muDownload($);
};

muDownload = function($)
{
    $('#download').removeClass('hidden');
    var downloadUrl = "download.php?format=json&id_gruppo=" + getIdGruppo();
    jQuery.ajax({
        "url": downloadUrl,
        "type": "GET"
    }).success(function (result) {
        var response = JSON.parse(result);
        if (response.error)
        {
            console.log(response);
            $('#download span.result').html("Errore! " + response.title);
            return;
        }
        $('#download span.result').html("OK");
        muProcess($);
    }).error(function () {
        console.log("Ajax error");
        $('#download span.result').html("Errore!");
    });
};

muProcess = function($)
{
    $('#process').removeClass('hidden');
    processUrl = "process.php?format=json&id_gruppo=" + getIdGruppo();
    jQuery.ajax({
        "url": processUrl,
        "type": "GET"
    }).success(function (result) {
        var response = JSON.parse(result);
        if (response.error)
        {
            console.log(response);
            $('#process span.result').html("Errore! " + response.title);
            return;
        }
        $('#process span.result').html("OK");
        muImport($);
    }).error(function () {
        console.log("Ajax error");
        $('#process span.result').html("Errore!");
    });
};

muImport = function($)
{
    $('#import').removeClass('hidden');
    importUrl = "import.php?format=json&id_gruppo=" + getIdGruppo();
    jQuery.ajax({
        "url": importUrl,
        "type": "GET"
    }).success(function (result) {
        var response = JSON.parse(result);
        if (response.error)
        {
            console.log(response);
            $('#import span.result').html("Errore! " + response.title);
            return;
        }
        $('#import span.result').html("OK");
        muFinish($);
    }).error(function () {
        console.log("Ajax error");
        $('#import span.result').html("Errore!");
    });
};

muFinish = function($)
{
    $('#finish').removeClass('hidden');
    $('#finish span.result').html(getIdGruppo());
};

function getIdGruppo()
{
    var values = getUrlVars();
    return values['id_gruppo'];
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}