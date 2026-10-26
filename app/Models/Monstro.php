<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Monstro
 * 
 * @property int $monstro_id
 * @property string $monstro_nome
 * @property int $drop_xp
 * 
 * @property Collection|Missao[] $missaos
 *
 * @package App\Models
 */
class Monstro extends Model
{
	protected $table = 'monstro';
	protected $primaryKey = 'monstro_id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'monstro_id' => 'int',
		'drop_xp' => 'int'
	];

	protected $fillable = [
		'monstro_nome',
		'drop_xp'
	];

	public function missaos()
	{
		return $this->hasMany(Missao::class, 'id_monstro');
	}
}
