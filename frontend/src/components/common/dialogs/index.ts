export const dialogProps = {
  /**
   * Цвет при наведении на кнопку отмены
   */
  buttonCancelHoverColor: 'negative',

  /**
   * Цвет при наведении на кнопку отмены
   */
  buttonCancelLabel: 'Отмена',

  /**
   * Цвет при наведении на кнопку отмены
   */
  buttonCancelTooltip: 'Отмена/Закрыть',

  /**
   * Цвет кнопки открытия диалогового окна
   */
  buttonColor: 'primary',

  /**
   * Цвет кнопки сброса формы при наведении
   */
  buttonResetHoverColor: 'warning',

  /**
   * Цвет кнопки подтверждения формы при наведении
   */
  buttonSuccessHoverColor: 'primary',

  /**
   * Текст кнопки подтверждения формы
   */
  buttonSuccessLabel: 'Выбрать',

  /**
   * Tooltip кнопки подтверждения формы при наведении
   */
  buttonSuccessTooltip: '',

  /**
   * Текст кнопки активации диалога
   */
  buttonText: '',

  /**
   * Tooltip кнопки активации диалога
   */
  buttonTooltip: '',

  /**
   * Использование кнопки отмены
   */
  cancelButton: true,

  /**
   * Классы для контента диалога
   */
  contentWrapperClasses: '',

  /**
   * Блокировка кнопки активации диалога
   */
  disabled: false,

  /**
   * Цвет фона заголовка диалога
   */
  headerBgColor: 'primary',

  /**
   * Цвет текста заголовка диалога
   */
  headerTextColor: 'white',

  /**
   * Заголовок диалогового окна
   */
  headerText: '',

  /**
   * Ожидание кнопки активации диалога
   */
  loading: false,

  /**
   * Кнопка открытия диалого в режиме ожидания
   */
  loadingOpenDialog: false,

  /**
   * Сделать диалог во весь экран
   */
  maximized: false,

  /**
   * Максимальная ширина диалога в пикселях
   */
  maxWidth: 600,

  modelValue: false,

  /**
   * Флаг необходимости использовать кнопку активации диалога
   */
  openDialogButton: true,

  /**
   * Флаг отмены закрытия диалога при клике вне диалога
   */
  persistent: true,

  /**
   * Использование кнопки сброса формы
   */
  resetButton: true,

  /**
   * Использование кнопки подтверждения
   */
  successButton: true,
};

export type DialogProps = typeof dialogProps;

export const dialogEmits = [
  'onCancel',
  'onClose',
  'onOpen',
  'onReset',
  'onSuccess',
  'update:modelValue',
];
