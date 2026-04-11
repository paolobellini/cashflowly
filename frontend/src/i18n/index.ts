import { createI18n } from 'vue-i18n'
import en from './locales/en'
import it from './locales/it'

const defaultLocale = import.meta.env.VITE_DEFAULT_LOCALE ?? 'it'
const fallbackLocale = import.meta.env.VITE_FALLBACK_LOCALE ?? 'en'
const supportedLocales = ['en', 'it']

function detectLocale(): string {
  const browserLocale = navigator.language.split('-')[0] ?? defaultLocale
  return supportedLocales.includes(browserLocale) ? browserLocale : defaultLocale
}

const i18n = createI18n({
  legacy: false,
  locale: detectLocale(),
  fallbackLocale,
  messages: { en, it },
})

export default i18n
