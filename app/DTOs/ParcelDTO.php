<?php

namespace App\DTOs;

class ParcelDTO
{
    private int $businessId;

    private string $sourceName;

    private string $sourceAddress;

    private string $sourcePhone;

    private float $sourceLat;

    private float $sourceLong;

    private string $destinationName;

    private string $destinationAddress;

    private string $destinationPhone;

    private float $destinationLat;

    private float $destinationLong;


    public function getSourceName(): string
    {
        return $this->sourceName;
    }


    public function getBusinessId(): int
    {
        return $this->businessId;
    }


    public function setBusinessId(int $businessId): self
    {
        $this->businessId = $businessId;
        return $this;
    }


    public function setSourceName(string $sourceName): self
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceAddress(): string
    {
        return $this->sourceAddress;
    }


    public function setSourceAddress(string $sourceAddress): self
    {
        $this->sourceAddress = $sourceAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourcePhone(): string
    {
        return $this->sourcePhone;
    }


    public function setSourcePhone(string $sourcePhone): self
    {
        $this->sourcePhone = $sourcePhone;
        return $this;
    }


    public function getSourceLat(): float
    {
        return $this->sourceLat;
    }


    public function setSourceLat(float $sourceLat): self
    {
        $this->sourceLat = $sourceLat;
        return $this;
    }


    public function getSourceLong(): float
    {
        return $this->sourceLong;
    }


    public function setSourceLong(float $sourceLong): self
    {
        $this->sourceLong = $sourceLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationName(): string
    {
        return $this->destinationName;
    }


    public function setDestinationName(string $destinationName): self
    {
        $this->destinationName = $destinationName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationAddress(): string
    {
        return $this->destinationAddress;
    }


    public function setDestinationAddress(string $destinationAddress): self
    {
        $this->destinationAddress = $destinationAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationPhone(): string
    {
        return $this->destinationPhone;
    }


    public function setDestinationPhone(string $destinationPhone): self
    {
        $this->destinationPhone = $destinationPhone;
        return $this;
    }


    public function getDestinationLat(): float
    {
        return $this->destinationLat;
    }


    public function setDestinationLat(float $destinationLat): self
    {
        $this->destinationLat = $destinationLat;
        return $this;
    }

    public function getDestinationLong(): float
    {
        return $this->destinationLong;
    }


    public function setDestinationLong(float $destinationLong): self
    {
        $this->destinationLong = $destinationLong;
        return $this;
    }


}
