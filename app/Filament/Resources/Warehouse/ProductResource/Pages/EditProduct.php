<?php

namespace App\Filament\Resources\Warehouse\ProductResource\Pages;

use App\Filament\Resources\Warehouse\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
