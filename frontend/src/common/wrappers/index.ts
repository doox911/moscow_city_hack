/**
 * Constants
 */
 import Config from 'Constants/config';

/**
 * Notifications
 */
 import { Notify } from 'quasar';

 /**
 * Types
 */
import type { AxiosError } from 'axios';
import type { QueryWrapperOptions } from 'Src/types';

export async function requestWrapper(options: QueryWrapperOptions) {
  let is_success = true;

  try {
    const { success_message = '' } = options;

    await options.success();

    if (success_message) {
      Notify.create({
        actions: [{ icon: 'close', color: 'white' }],
        color: Config.NotifySuccessColor,
        message: success_message,
        position: 'bottom-right',
        progress: true,
        timeout: Config.NotifyDefaultTimeout,
      });
    }
  } catch (error) {
    let error_message = 'Ошибка выполнения запроса';

    if (options.error_message) {
      error_message = options.error_message;
    } else if ((error as AxiosError).response?.data?.message?.length) {
      error_message = (error as AxiosError).response?.data.message;
    }

    Notify.create({
      actions: [{ icon: 'close', color: 'white' }],
      color: Config.NotifyErrorColor,
      message: error_message,
      position: 'bottom-right',
      progress: true,
      timeout: Config.NotifyDefaultTimeout,
    });

    if (typeof options.error === 'function') {
      options.error(error);
    }

    is_success = false;
  } finally {
    const { always_message = '' } = options;

    if (typeof options.always === 'function') {
      if (always_message) {
        Notify.create({
          actions: [{ icon: 'close', color: 'white' }],
          message: always_message,
          position: 'bottom-right',
          progress: true,
          timeout: Config.NotifyDefaultTimeout,
        });
      }

      options.always();
    }
  }

  return {
    is_success,
  };
}
