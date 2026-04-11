export type TransactionType = 'income' | 'expense'

export interface Wallet {
  id: string
  name: string
  type: string
  currency: string
  initial_balance: number
  is_default: boolean
  color: string
  description?: string
}

export interface Category {
  id: string
  name: string
  type: TransactionType
  icon: string
  color: string
}

export interface Transaction {
  id: string
  wallet_id: string
  category_id: string
  type: TransactionType
  amount: number
  date: string
  description?: string
  recurrence_id?: string
  is_recurrence?: boolean
}

export interface Recurrence {
  id: string
  wallet_id: string
  category_id: string
  type: TransactionType
  amount: number
  description?: string
  frequency: 'daily' | 'weekly' | 'monthly' | 'yearly'
  start_date: string
  end_date?: string
  is_active: boolean
  last_generated_at?: string
}

export interface TransactionWithDetails extends Transaction {
  wallet: Wallet
  category: Category
  recurrence?: Recurrence
}
