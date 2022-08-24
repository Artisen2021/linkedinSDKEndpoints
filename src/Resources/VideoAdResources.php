<?php
declare(strict_types=1);


namespace Artisen2021\LinkedInSDK\Resources;

class VideoAdResources extends AdResources
{
    protected int $externalId;
    protected string $callToAction;

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
}
