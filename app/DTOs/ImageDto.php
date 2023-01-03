<?php

namespace App\DTOs;

class ImageDTO extends DTO
{
    protected string $url;

    public static function mockCollection($static = true, $count = 2)
    {


        if ($static) {
            for ($i = 1; $i <= $count; $i++) {
                $images[] = static::make([
                    'url' =>  fake()->imageUrl(),
                    'description' => fake()->title()
                ]);
            }
        } else {
            $images = [
                static::make([
                    'url' => url('/storage/images/first.jpg'),
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3738-6564-4538-a231-346133636439/9.png',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3665-3738-4534-a533-396166613738/iqos-cover1.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => url('/storage/images/first.jpg'),
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3738-6564-4538-a231-346133636439/9.png',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3665-3738-4534-a533-396166613738/iqos-cover1.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild6362-3335-4561-a231-326137393363/r-finance-10.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3738-6564-4538-a231-346133636439/9.png',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3665-3738-4534-a533-396166613738/iqos-cover1.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3738-6564-4538-a231-346133636439/9.png',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3665-3738-4534-a533-396166613738/iqos-cover1.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild3431-3938-4161-a233-616239363437/bbq-photo-5.jpeg',
                    'description' => fake()->title()
                ]),
                static::make([
                    'url' => 'https://static.tildacdn.com/tild6362-3335-4561-a231-326137393363/r-finance-10.jpeg',
                    'description' => fake()->title()
                ]),
   
            ];
        }

        return $images;
    }

    public static function mock(){
        return collect(static::mockCollection())->first();
    }
}
