<?php

function getColorForMark($mark): string
{
    if ($mark >= 90) return "#16b300";
    elseif ($mark >= 75) return "#0496be";
    elseif ($mark >= 60) return "#fbc210";
    elseif ($mark >= 1) return "red";
    else return "white";
}
