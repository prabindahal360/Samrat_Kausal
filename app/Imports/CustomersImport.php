<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       return new Customer([
            'customer_id' => $row['customer_id'] ?? null,
            'name' => $row['name'] ?? null,
            'email' => $row['email'] ?? null,
            'age' => $row['age'] ?? null,
            'gender' => $row['gender'] ?? null,
            'country' => $row['country'] ?? null,
            'annual_income' => $row['annual_income'] ?? null,
            'spending_score' => $row['spending_score'] ?? null,
            'num_purchases' => $row['num_purchases'] ?? 0,
            'avg_purchase_value' => $row['avg_purchase_value'] ?? null,
            'membership_years' => $row['membership_years'] ?? null,
            'website_visits_per_month' => $row['website_visits_per_month'] ?? null,
            'cart_abandon_rate' => $row['cart_abandon_rate'] ?? null,
            'churned' => $row['churned'] ?? 0,
            'feedback_text' => $row['feedback_text'] ?? null,
            'last_purchase_date' => $row['last_purchase_date'] ?? null,
        ]);
    }
}
