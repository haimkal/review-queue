import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import BaseSelect from '../BaseSelect.vue';

describe('BaseSelect', () => {
  it('renders options and emits update:modelValue on change', async () => {
    const wrapper = mount(BaseSelect, {
      props: {
        label: 'State',
        modelValue: '',
        options: [
          { label: 'Approved', value: 'approved' },
          { label: 'Pending', value: 'pending' },
        ],
      },
    });

    const select = wrapper.get('select');
    await select.setValue('pending');

    expect(wrapper.emitted('update:modelValue')).toBeTruthy();
    expect(wrapper.emitted('update:modelValue')[0]).toEqual(['pending']);
  });
});
