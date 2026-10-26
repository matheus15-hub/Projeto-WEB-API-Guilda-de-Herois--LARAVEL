<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MissoesCompletada
 * 
 * @property int $id_aventureiro
 * @property int $id_missao
 * @property int|null $ouro_recebido
 * @property int|null $xp_recebido
 * 
 * @property Aventureiro $aventureiro
 * @property Missao $missao
 *
 * @package App\Models
 */
class MissoesCompletada extends Model
{
	protected $table = 'missoes_completadas';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'id_aventureiro' => 'int',
		'id_missao' => 'int',
		'ouro_recebido' => 'int',
		'xp_recebido' => 'int'
	];

	protected $fillable = [
		'ouro_recebido',
		'xp_recebido'
	];

	public function aventureiro()
	{
		return $this->belongsTo(Aventureiro::class, 'id_aventureiro');
	}

	public function missao()
	{
		return $this->belongsTo(Missao::class, 'id_missao');
	}
}
