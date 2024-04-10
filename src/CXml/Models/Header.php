<?php

namespace CXml\Models;

use CXml\Models\Responses\ResponseInterface;

class Header
{
    private $senderIdentity;
    private $senderSharedSecret;
    private $fromIdentity;
    private $toIdentity;
    private $userAgent;

    public function getFromIdentity()
    {
        return $this->fromIdentity;
    }

    public function setFromIdentity($fromIdentity): self
    {
        $this->fromIdentity = $fromIdentity;
        return $this;
    }

    public function getToIdentity()
    {
        return $this->toIdentity;
    }

    public function setToIdentity($toIdentity): self
    {
        $this->toIdentity = $toIdentity;
        return $this;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSenderIdentity()
    {
        return $this->senderIdentity;
    }

    public function setSenderIdentity($senderIdentity): self
    {
        $this->senderIdentity = $senderIdentity;
        return $this;
    }

    public function getSenderSharedSecret()
    {
        return $this->senderSharedSecret;
    }

    public function setSenderSharedSecret($senderSharedSecret): self
    {
        $this->senderSharedSecret = $senderSharedSecret;
        return $this;
    }

    public function parse(\SimpleXMLElement $headerXml) : void
    {
        $this->senderIdentity = (string)$headerXml->xpath('Sender/Credential/Identity')[0];
        $this->senderSharedSecret = (string)$headerXml->xpath('Sender/Credential/SharedSecret')[0];
    }

    public function render(\SimpleXMLElement $parentNode) : void
    {
        $headerNode = $parentNode->addChild('Header');

        $this->addNode($headerNode, 'From', $this->getFromIdentity() ?? 'Unknown');
        $this->addNode($headerNode, 'To', $this->getToIdentity() ?? 'Unknown');
        $this->addNode($headerNode, 'Sender', $this->getSenderIdentity() ?? 'Unknown')
            ->addChild('UserAgent', $this->getUserAgent() ?? 'Unknown');
    }

    private function addNode(\SimpleXMLElement $parentNode, string $nodeName, string $identity) : \SimpleXMLElement
    {
        $node = $parentNode->addChild($nodeName);

        $credentialNode = $node->addChild('Credential');
        $credentialNode->addAttribute('domain', 'NetworkID');

        $credentialNode->addChild('Identity', $identity);

        return $node;
    }
}
