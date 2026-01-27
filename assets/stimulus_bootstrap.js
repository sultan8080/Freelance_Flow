import { startStimulusApp } from '@symfony/stimulus-bundle';
import FlashController from './controllers/flash_controller.js';
import FormCollectionController from './controllers/form-collection_controller.js';
const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register('flash', FlashController);
app.register('form-collection', FormCollectionController);