<?php

namespace App\Enum;

enum ResourceBlockType: string
{
    case HEADING = 'heading';
    case SUB_HEADING = 'sub_heading';
    case PARAGRAPH = 'paragraph';
    case IMAGE = 'image';
    case QUOTE = 'quote';

    public function label(): string
    {
        return match ($this) {
            self::HEADING => 'Heading',
            self::SUB_HEADING => 'Sub Heading',
            self::PARAGRAPH => 'Paragraph',
            self::IMAGE => 'Image',
            self::QUOTE => 'Quote',
        };
    }
}
