import { boot } from 'quasar/wrappers';

export default boot(({ app }) => {
  app.config.errorHandler = (err) => {
    // eslint-disable-next-line no-console
    console.log(err);
  };
});
