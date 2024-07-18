<?php

namespace App\Http\Requests\API\Social\Google;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Регистрация",
 *      description="",
 *      type="object",
 *      required={"text"}
 * )
 */
class GoogleCreateEventRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="start_date",
     *      description="Дата начала события",
     *      example="2024-07-18 11:00"
     * )
     */
    public string $start_date;

    /**
     * @OA\Property(
     *      title="end_date",
     *      description="Дата окончания события",
     *      example="2024-07-18 12:00"
     * )
     */
    public string $end_date;

    /**
     * @OA\Property(
     *      title="text",
     *      description="Текст в календаре",
     *      example="Текст в ячейке календаря"
     * )
     */
    public string $text;

    /**
     * @OA\Property(
     *      title="is_conference",
     *      description="Нужна ли ссылка на конференцию",
     *      example=1
     * )
     */
    public bool $is_conference;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "start_date" => "nullable|string",
            "end_date" => "nullable|string",
            "text" => "required|string",
            "is_conference" => "nullable|bool",
        ];
    }

    public function messages(): array
    {
        return [
            'start_date' => [
                'string' => 'Invalid data type'
            ],
            'end_date' => [
                'string' => 'Invalid data type'
            ],
            'text' => [
                'required' => 'The field is required',
                'string' => 'Invalid data type'
            ],
            'is_сonference' => [
                'bool' => 'Invalid data type'
            ],
        ];
    }
}
