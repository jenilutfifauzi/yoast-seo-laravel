<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Presenters;

use Jenlut\YoastSeoLaravel\Data\SeoDocument;

final class TitlePresenter
{
    public function present(SeoDocument $document): string
    {
        if ($document->title === null) {
            return '';
        }

        return '<title>'.self::escape($document->title).'</title>';
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
