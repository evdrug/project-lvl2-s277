<?php

namespace Differ\Reports;

function reportJson($data)
{
    return json_encode($data, JSON_FORCE_OBJECT);
}
