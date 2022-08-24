<?php
declare(strict_types=1);


namespace Artisen2021\LinkedInSDK\Resources;

class ImageAdResources extends AdResources
{
    protected int $externalId;
    protected string $callToAction;
    protected string $status;

    public function setExternalId($externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function setCallToAction($callToAction): void
    {
        $this->callToAction = $callToAction;
    }

    public function getCallToAction(): string
    {
        return $this->callToAction;
    }


    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}