import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import BaseButton from '../BaseButton.vue';

describe('BaseButton', () => {
  it('emits click when pressed', async () => {
    const wrapper = mount(BaseButton, {
      props: { variant: 'primary' },
      slots: { default: 'Refresh' },
    });

    await wrapper.get('button').trigger('click');

    expect(wrapper.emitted('click')).toBeTruthy();
    expect(wrapper.emitted('click').length).toBe(1);
  });

  it('does not emit click when disabled', async () => {
    const wrapper = mount(BaseButton, {
      props: { disabled: true },
      slots: { default: 'Refresh' },
    });

    await wrapper.get('button').trigger('click');

    expect(wrapper.emitted('click')).toBeFalsy();
  });

  it('adds active class when active=true', () => {
    const wrapper = mount(BaseButton, {
      props: { variant: 'seg', active: true },
      slots: { default: 'Reviewer' },
    });

    expect(wrapper.get('button').classes()).toContain('active');
  });
});
