<?php

namespace App\Filament\Resources\Anagrafiche;

use App\Filament\Resources\Anagrafiche\MagazzinoResource\Pages;
use App\Filament\Resources\Anagrafiche\MagazzinoResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anagrafiche\Magazzino;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MagazzinoResource extends Resource
{
    protected static ?string $model = Magazzino::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'magazzini';

    protected static ?string $label = 'magazzino';

    protected static ?string $pluralLabel = 'Magazzini';

    protected static ?int $navigationSort = 5;

    protected static function getNavigationGroup(): ?string
    {
        return 'Anagrafiche';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Magazzini';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('codice')
                    ->required()
                    ->unique(Magazzino::class, 'codice', fn ($record) => $record)
                    ->placeholder('Codice'),
                    Forms\Components\TextInput::make('descrizione')
                        ->required()
                        ->maxLength(255),
                ])
                ->columnSpan([
                    'sm' => 2,
                ]),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Toggle::make('attivo')
                    ->label('Attivo')
                    ->default(true)
                    ->reactive()
                    ->afterStateUpdated(
                        function ($state, \Closure $get, callable $set) {
                            if ($state == true) {
                                $set('data.isActive', false);
                                $set('attivato_il', now());
                                $set('disattivato_il', null);
                            } else {
                                $set('data.isActive', true);
                                if ($get('attivato_il') == '') {
                                    $set('attivato_il', now());
                                }
                                $set('disattivato_il', now());
                            }

                        }
                    ),
                    Forms\Components\Placeholder::make('attivato_il_placeholder')
                    ->label('Attivato il')
                    ->content(fn (?Magazzino $record): string => $record ? date_format($record->attivato_il,'d/m/Y') : '-'),
                // Forms\Components\Placeholder::make('disattivato_il_placeholder')
                //     ->label('Disattivato il')
                //     ->content(fn (?Magazzino $record): string => $record ? $record->disattivato_il : '-'),
                    Forms\Components\Hidden::make('attivato_il')
                    ->default(now()),
                    Forms\Components\Hidden::make('disattivato_il'),
                ])
                ->columnSpan(1),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Textarea::make('nota'),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ]),

            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codice'),
                Tables\Columns\TextColumn::make('descrizione'),
                // Tables\Columns\TextColumn::make('attivato_il')
                //     ->date(),
                // Tables\Columns\TextColumn::make('disattivato_il')
                //     ->date(),
                Tables\Columns\TextColumn::make('nota'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMagazzino::route('/'),
            'create' => Pages\CreateMagazzino::route('/create'),
            'edit' => Pages\EditMagazzino::route('/{record}/edit'),
        ];
    }


    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Descrizione' => $record->descrizione,
        ];
    }
}
