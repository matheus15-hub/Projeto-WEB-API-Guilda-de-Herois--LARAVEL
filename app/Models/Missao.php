<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Missao
 * 
 * @property int $missao_id
 * @property string $missao_nome
 * @property string|null $dificuldade
 * @property int|null $recompensa_ouro
 * @property int|null $id_monstro
 * @property int|null $quantidade_monstro
 * 
 * @property Monstro|null $monstro
 * @property Collection|MissoesCompletada[] $missoes_completadas
 *
 * @package App\Models
 */
class Missao extends Model
{
	protected $table = 'missao';
	protected $primaryKey = 'missao_id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'missao_id' => 'int',
		'recompensa_ouro' => 'int',
		'id_monstro' => 'int',
		'quantidade_monstro' => 'int'
	];

	protected $fillable = [
		'missao_nome',
		'dificuldade',
		'recompensa_ouro',
		'id_monstro',
		'quantidade_monstro'
	];

	public function monstro()
	{
		return $this->belongsTo(Monstro::class, 'id_monstro');
	}

	public function missoes_completadas()
	{
		return $this->hasMany(MissoesCompletada::class, 'id_missao');
	}
}
