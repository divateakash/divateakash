<?php

namespace App\Http\Requests;

use App\Account;
use App\Catalog;
use App\MessageGroup;
use App\Offer;
use App\Rules\BelongsToTeam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'carriers' => ['array'],
            'link_type' => ['required', Rule::in(['no_link', 'static', 'hash', 'amazon'])],
            'message_type' => 'required|string',
            'domain_group_id' => ['required_if:link_type,hash'],
            'account_id' => ['required', new BelongsToTeam(Account::class, team())],
            'reply_account_id' => ['required_if:message_type,keyword_reply', new BelongsToTeam(Account::class, team())],
            'message_group_id' => ['required', new BelongsToTeam(MessageGroup::class, team())],
            'reply_message_group_id' => ['required_if:message_type,keyword_reply', new BelongsToTeam(MessageGroup::class, team())],
            'offer_id' => ['required', new BelongsToTeam(Offer::class, team())],
            'catalog_id.*' => ['required', 'integer', new BelongsToTeam(Catalog::class, team())],
            'repliers_catalog_id' => ['nullable', 'integer', new BelongsToTeam(Catalog::class, team())],
            'skip' => ['nullable', 'numeric'],
            'limit' => ['nullable', 'numeric'],
            'drip_wait_hours' => ['sometimes', 'numeric'],
            'drip_skip_weekends' => ['sometimes'],
            'drip_time_limit' => ['nullable', 'date_format:H:i'],
        ];
    }
}
