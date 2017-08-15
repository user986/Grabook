<?php namespace App\Http\Controllers\Api\v1;
use App\Models\User;
use League\Fractal\TransformerAbstract;
class UserTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [

  ];

  /**
   * List of resources to automatically include
   *
   * @var array
   */
  protected $defaultIncludes = [

  ];


  /**
   * Turn this item object into a generic array
   *
   * @param Address $item
   * @return array
   */
  public function transform(User $user)
  {
    return [
        'id'          => (int) $user->id,
        'name'         => $user->email,
    ];
  }
}