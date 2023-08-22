export interface TransactionTypeDTO {
    id: number;
    status_id: number;
    status_description: string;
    type_id: number;
    type_description: string;
    customer_id: number;
    customer_account: string;
    customer_name: string;
    customer_email: string;
    amount: number;
    amount_formatted: string;
    description: string;
    check_url: string;
    created_at: string;
    updated_at: string;
    date_formatted: string;
}
