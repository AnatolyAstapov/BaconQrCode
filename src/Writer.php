<?php

namespace BaconQrCode;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Exception\InvalidArgumentException;
use BaconQrCode\Renderer\RendererInterface;

/**
 * QR code writer.
 */
final class Writer
{
    /**
     * Renderer instance.
     *
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Creates a new writer with a specific renderer.
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Writes QR code and returns it as string.
     *
     * Content is a string which *should* be encoded in UTF-8, in case there are
     * non ASCII-characters present.
     *
     * @throws InvalidArgumentException if the content is empty
     */
    public function writeString(
        $content,
        $encoding = null,
        $ecLevel = null,
        $forcedVersion = null
    ) {
        if (is_null($encoding)) {
            $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING;
        }

        if (strlen($content) === 0) {
            throw new InvalidArgumentException('Found empty contents');
        }

        if (null === $ecLevel) {
            $ecLevel = ErrorCorrectionLevel::L();
        }

        return $this->renderer->render(Encoder::encode($content, $ecLevel, $encoding, $forcedVersion));
    }

    /**
     * Writes QR code to a file.
     *
     * @see Writer::writeString()
     */
    public function writeFile(
        $content,
        $filename,
        $encoding = null,
        $ecLevel = null,
        $forcedVersion = null
    ) {
        if (is_null($encoding)) {
            $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING;
        }
        file_put_contents($filename, $this->writeString($content, $encoding, $ecLevel, $forcedVersion));
    }
}
