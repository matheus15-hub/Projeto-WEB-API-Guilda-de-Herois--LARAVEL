<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Classe
 * 
 * @property int $classe_id
 * @property string $classe_nome
 * @property string|null $descricao
 * @property string $raridade
 * 
 * @property Collection|Aventureiro[] $aventureiros
 *
 * @package App\Models
 */
class Classe extends Model
{
	protected $table = 'classe';
	protected $primaryKey = 'classe_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'classe_id' => 'int'
	];

	protected $fillable = [
		'classe_nome',
		'descricao',
		'raridade'
	];

	public function aventureiros()
	{
		return $this->hasMany(Aventureiro::class, 'id_classe');
	}
}
