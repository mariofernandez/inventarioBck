<?php
function getRequerido()
{
    return 'This Field is Required...';
}
function getError()
{
    return 'Missing Required Fields';
}
function getMsgDatos()
{
    return 'Data Send';
}
function getCiaSucces()
{
    return 'Company Saved.';
}
function getCiaUpdt()
{
    return 'Company Udated.';
}
function getCiaDel()
{
    return 'Company Deleted.';
}
function getCiaDelError()
{
    return 'It\'s Required to Select a Company ...';
}
function getMsgCiaAsig()
{
    return 'Company(ies) Asigned.';
}

// Terminales
function getTerSucces()
{
    return 'Terminal Saved.';
}
function getTerUpdt()
{
    return 'Terminal Updated.';
}
function getTerDel()
{
    return 'Terminal Deleted.';
}
function getTerDelError()
{
    return 'It\'s Required to Select a Terminal ...';
}
function getTerError($terminal)
{
    return "<br>Terminal " . $terminal . " Unreacheable";
}

// Grupos
function getGpoSucces()
{
    return 'Group Saved.';
}
function getGpoUpdt()
{
    return 'Group Updated.';
}
function getGpoDel()
{
    return 'Group Deleted.';
}
function getGpoDelError()
{
    return 'It\'s Required to Select a Group ...';
}
function getMsgGpoAsig()
{
    return 'Group(s) Asigned.';
}

// Usuarios / Empleados
function getUsrSucces()
{
    return 'User Saved.';
}
function getUsrUpdt()
{
    return 'User Updated.';
}
function getUsrDel()
{
    return 'User Deleted.';
}
function getUsrDelError()
{
    return 'It\'s Required to Select a User ...';
}
function getMsgEmpAsig()
{
    return 'Employee(s) Asigned.';
}
?>