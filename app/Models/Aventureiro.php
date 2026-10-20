<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aventureiro
 * 
 * @property int $aventureiro_id
 * @property string $aventureiro_nome
 * @property int|null $idade
 * @property int|null $experiencia
 * @property int|null $id_classe
 * @property int|null $id_guilda
 * @property int|null $ouro
 * 
 * @property Classe|null $classe
 * @property Guilda|null $guilda
 * @property Collection|MissoesCompletada[] $missoes_completadas
 *
 * @package App\Models
 */
class Aventureiro extends Model
{
	protected $table = 'aventureiro';
	protected $primaryKey = 'aventureiro_id';
	protected $keyType = 'int';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'aventureiro_id' => 'int',
		'idade' => 'int',
		'experiencia' => 'int',
		'id_classe' => 'int',
		'id_guilda' => 'int',
		'ouro' => 'int'
	];

	protected $fillable = [
		'aventureiro_nome',
		'idade',
		'experiencia',
		'id_classe',
		'id_guilda',
		'ouro'
	];

	public function classe()
	{
		return $this->belongsTo(Classe::class, 'id_classe');
	}

	public function guilda()
	{
		return $this->belongsTo(Guilda::class, 'id_guilda');
	}

	public function missoes_completadas()
	{
		return $this->hasMany(MissoesCompletada::class, 'id_aventureiro');
	}
}
