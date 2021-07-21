<?php

namespace App\Services;

class OutboundFilteringService
{
    public function filter($query)
    {
        return $query
            ->when(request('filter') == 'pending', fn($query) => $query->pending(), fn($query) => $query->sent())
            ->when(request('filter') == 'failure', function ($query) {
                return $query->where(function ($subQuery) {
                    return $subQuery->failed()->orWhereHas('deliveryReport', fn($dlr) => $dlr->rejected());
                });
            })
            ->when(request('filter') == 'success', function ($query) {
                return $query->succeeded();
            })
            ->when(request('filter') == 'replies', function ($query) {
                return $query->reply();
            })
            ->when(request('filter') == 'delivered', function ($query) {
                return $query->whereHas('deliveryReport', fn($dlr) => $dlr->delivered());
            })
            ->when(request('dlrStatus'), function ($query) {
                return $query->whereHas('deliveryReport', fn($subQuery) => $subQuery->where('status', request('dlrStatus')));
            });
    }
}
