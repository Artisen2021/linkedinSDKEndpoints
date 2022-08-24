<?php
declare(strict_types=1);


namespace Artisen2021\LinkedInSDK\Event;

class LinkedInRequestPendingPagesEvent
{
    private int $pageId;

    public function __construct(int $pageId)
    {
        $this->pageId = $pageId;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }
}