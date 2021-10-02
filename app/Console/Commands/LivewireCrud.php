<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LivewireCrud extends Command
{
    public $dbName;
    public $modelName;
    public $property = "";
    public $rules = "[\n";
    public $data = "";
    public $propertyCreate = "";
    public $propertyEdit = "";
    public $search = "";
    public $singularTableName;

    public $form = "";
    public $thTable = "";
    public $tbodyTable = "";
    public $totalColumn = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:crud {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CRUD Livewire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->file = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->dbName = DB::connection()->getDatabaseName();
        $this->tableName = $this->argument('table');
        $columns = DB::table('information_schema.COLUMNS')
            ->select('column_name', 'column_key')
            ->where('TABLE_SCHEMA', $this->dbName)
            ->where('TABLE_NAME', $this->tableName)
            ->get();
        if ($columns) {
            $this->gatherParameters($columns);
            $this->generateLivewireClass();
            $this->generateLivewireView();
        } else {
            $this->info("You don't have columns in the table or you don't type the table name");
        }
    }

    protected function gatherParameters($columns)
    {
        $column = DB::table('information_schema.COLUMNS')
            ->select('column_name', 'column_key')
            ->where('TABLE_SCHEMA', $this->dbName)
            ->where('TABLE_NAME', $this->tableName)
            ->where('COLUMN_KEY', 'PRI')
            ->first();

        // Parameter Component
        $this->modelName = Str::ucfirst(str_replace('_', '', Str::camel(Str::singular($this->tableName))));
        $this->property = "\t";
        $this->rules = "[\n\t\t\t";
        $this->data = "[\n\t\t\t";
        $this->propertyCreate = "\t\t\$this->id" . $this->modelName . " = '';\n\t\t";
        $this->propertyEdit = "\t\t";
        $this->search = "";
        $this->singularTableName = Str::singular($this->tableName);

        // Parameter View
        $i = 1;
        foreach ($columns as $c) {
            // Parameter Component
            if ($c->column_key != 'PRI' && $c->column_name != 'created_at' && $c->column_name != 'updated_at' && $c->column_name != 'deleted_at') {
                $this->property .= "public $" . $c->column_name . ";\n\t";
                $this->rules .= "'" . $c->column_name . "' => 'required',\n\t\t\t";
                $this->data .= "'" . $c->column_name . "' => \$this->" . $c->column_name . ",\n\t\t\t";
                $this->propertyCreate .= "\$this->" . $c->column_name . " = '';\n\t\t";
                $this->propertyEdit .= "\$this->" . $c->column_name . " = \$data->" . $c->column_name . ";\n\t\t";
                if ($i == 1) {
                    $this->search .= "where('" . $c->column_name . "', 'like', '%' . \$this->search . '%')\n";
                } else {
                    $this->search .= "\t\t\t\t->orWhere('" . $c->column_name . "', 'like', '%' . \$this->search . '%')\n";
                }

                // Parameter View
                $this->form .= "\t\t\t\t\t<div class='form-group'>
                    <label for='" . $c->column_name . "'>" . ucwords(str_replace("_", " ", $c->column_name)) . "</label>
                    <input type='text' id='" . $c->column_name . "' name='" . $c->column_name . "' class='form-control @if(\$errors->has(\"" . $c->column_name . "\")) is-invalid @endif' placeholder='" . ucwords(str_replace("_", " ", $c->column_name)) . "' wire:model.lazy='" . $c->column_name . "'>
                    @error('" . $c->column_name . "')
                    <div class='invalid-feedback'>
                        {{ \$message }}
                    </div>
                    @enderror
                </div>\n";
                $this->thTable .= "\t\t\t\t\t\t\t<th>" . ucwords(str_replace("_", " ", $c->column_name)) . "</th>\n";
                $this->tbodyTable .= "\t\t\t\t\t\t\t<td>{{ \$" . $this->singularTableName . "->" . $c->column_name . " }}</td>\n";
                $i++;
            }
        }
        $this->totalColumn = $i;
        $this->rules .= "]";
        $this->data .= "]";
    }

    protected function generateLivewireClass()
    {
        $fileOrigin = base_path('stubs/custom.livewire.stub');
        $fileDestination = base_path('app/Http/Livewire/' . $this->modelName . 'Component.php');

        $fileOriginalString = $this->file->get($fileOrigin);

        $replaceFileOriginalString = str_replace(['{{modelName}}', '{{property}}', '{{rules}}', '{{data}}', '{{propertyCreate}}', '{{propertyEdit}}', '{{search}}', '{{tableName}}', '{{singularTableName}}'], [$this->modelName, $this->property, $this->rules, $this->data, $this->propertyCreate, $this->propertyEdit, $this->search, $this->tableName, str_replace('_', '-', $this->singularTableName)], $fileOriginalString);
        $this->file->put($fileDestination, $replaceFileOriginalString);
    }

    protected function generateLivewireView()
    {
        $fileOrigin = base_path('stubs/custom.livewire.view.stub');
        $fileDestination = base_path('resources/views/livewire/' . str_replace('_', '-', $this->singularTableName) . '-component.blade.php');

        $fileOriginalString = $this->file->get($fileOrigin);
        $replaceFileOriginalString = str_replace(['{{modelName}}', '{{singularTableName}}', '{{form}}', '{{thTable}}', '{{tableName}}', '{{tbodyTable}}', '{{totalColumn}}', '{{modalName}}'], [$this->modelName, $this->singularTableName, $this->form, $this->thTable, $this->tableName, $this->tbodyTable, ($this->totalColumn + 1), str_replace('_', '-', $this->singularTableName)], $fileOriginalString);
        $this->file->put($fileDestination, $replaceFileOriginalString);
    }
}
