<?php

namespace App\Admin\Forms\Tabs;

use App\Models\Tag;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
use App\Models\JobCondition as Model;
use App\Models\JobConditionsCategory as Category;
use Illuminate\Support\Facades\DB;

class Conditions extends Form
{

    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Условия работы';

    /**
     * Handle the form request.
     *
     * @param  Request $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        DB::transaction(function () use ($request) {

            Category::query()->whereDoesntHave('conditions')->each(function (Category $item) {
                $item->conditions()->whereDoesntHave('vacancies')->delete();
            });


            collect($request->input('category'))->filter(function ($value, $key) {
                return $value['_remove_'] == 0;
            })->each(function ($item) {
                if ($item['_remove_'] == 1) {
                    return Category::query()->find($item['id'])->delete();
                }


                if (isset($item['id'])) {
                    $category = Category::query()->find($item['id']);
                    $category->update(['name' => $item['name']]);
                } else {
                    $category = Category::query()->firstOrCreate(['name' => $item['name']]);
                }

                if (isset($item['category_conditions'])) {

                    $items = collect($item['category_conditions'])->map(function ($item) use ($category) {
                        if (is_array($item)) {
                            if ($model = Model::query()->find($item['id'])) {
                                if (method_exists($model, 'update')) {
                                    $model->update(['name' => $item['name'], 'job_conditions_category_id' => $category->id]);
                                } else {
                                    $model =  Model::query()->create(['name' => $item, 'job_conditions_category_id' => $category->id]);
                                }

                                return $model->id;
                            }
                        } else {
                            $model =  Model::query()->firstOrCreate(['name' => $item, 'job_conditions_category_id' => $category->id]);
                            return $model->id;
                        }
                    })->toArray();

                    $category->conditions()->whereDoesntHave('vacancies')->whereNotIn('id',  $items)->delete();
                }
            });
        });



        admin_success('Processed successfully.');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->table('category', function ($table) {
            $table->hidden('id', 'Id');
            $table->text('name', 'Название')->required();

            $table->items('category_conditions', 'Условия работы')
                ->addVariables(
                    ['parent' => ['condition_category', 'Категория условий работы']]
                );
        });

        //    $this->list('tags', 'Теги вводятся без #')->min(1);
    }

    public function data()
    {
        return [
            'category' => Category::all()->map(function ($i) {
                return [
                    'name' => $i->name, 'id' => $i->id, 'category_conditions' => $i->conditions
                        ->map(fn ($i) => ['name' => $i->name, 'id' => $i->id])->toArray()
                ];
            })->toArray()
        ];
    }
}
