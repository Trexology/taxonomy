<?php namespace Devfactory\Taxonomy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TermRelation extends Eloquent {

  protected $fillable = [
    'term_id',
  ];

	protected $table = 'term_relations';

  public function relationable() {
    return $this->morphTo();
  }

	public function term() {
		return $this->belongsTo('Devfactory\Taxonomy\Term');
	}

}
