<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Guilda
 * 
 * @property int $guilda_id
 * @property string $guilda_nome
 * @property string|null $lema
 * 
 * @property Collection|Aventureiro[] $aventureiros
 *
 * @package App\Models
 */
class Guilda extends Model
{
	protected $table = 'guilda';
	protected $primaryKey = 'guilda_id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'guilda_id' => 'int'
	];

	protected $fillable = [
		'guilda_nome',
		'lema'
	];

	public function aventureiros()
	{
		return $this->hasMany(Aventureiro::class, 'id_guilda');
	}
}
