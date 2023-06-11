<?php
function page_clause($param)
{
    $page = intval($param);
    $offset = ($page - 1) * PAGE_SIZE;
    $page_clause = " LIMIT " . PAGE_SIZE . " OFFSET " . $offset;
    return $page_clause;
}