/**
 * @version     js/mailupper.js 2015-04-21 19:59:00 UTC zanardi
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
    alert("Continuo");
    muDownload($);
};

muDownload = function($)
{
    $('#download').removeClass('hidden');
    alert("Continuo");
    $('#download span.result').html('OK');
    alert("Continuo");
    muProcess($);
};

muProcess = function($)
{
    $('#process').removeClass('hidden');
    alert("Continuo");
    $('#process span.result').html('OK');
    alert("Continuo");
    muImport($);
};

muImport = function($)
{
    $('#import').removeClass('hidden');
    alert("Continuo");
    $('#import span.result').html('OK');
    alert("Continuo");
    muFinish($);
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