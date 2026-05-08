<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $crop_name
 * @property numeric $price_per_kg
 * @property \Illuminate\Support\Carbon $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice whereCropName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice wherePricePerKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityPrice whereUpdatedAt($value)
 */
	class CommodityPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nickname
 * @property numeric $area_size
 * @property numeric|null $lat
 * @property numeric|null $lng
 * @property array<array-key, mixed>|null $boundaries
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $area_in_hectare
 * @property-read mixed $coordinates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ubinan> $ubinans
 * @property-read int|null $ubinans_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LandFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereAreaSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereBoundaries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Land whereUserId($value)
 */
	class Land extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $land_id
 * @property numeric $sample_weight_kg
 * @property numeric $estimated_yield_kg
 * @property string $status
 * @property string $projected_harvest_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Land $land
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereEstimatedYieldKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereLandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereProjectedHarvestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereSampleWeightKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ubinan whereUpdatedAt($value)
 */
	class Ubinan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $role
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Land> $lands
 * @property-read int|null $lands_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ubinan> $ubinans
 * @property-read int|null $ubinans_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

