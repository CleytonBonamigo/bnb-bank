<?php

uses(\Turno\Common\Traits\TransformerUtility::class);

test('transformer utility trait without data', function () {
    expect($this->transform(null, false))->toBeNull();
    expect($this->transform(null))->toEqual(['data' => []]);
});
