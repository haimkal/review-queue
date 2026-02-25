import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import QueueControls from '../QueueControls.vue';

describe('QueueControls', () => {
  it('emits update:q on typing and refresh on Enter', async () => {
    const wrapper = mount(QueueControls, {
      props: {
        stateFilter: '',
        q: '',
        sort: 'created_at',
        dir: 'desc',
        loading: false,
      },
    });

    const input = wrapper.get('input');
    await input.setValue('bitcoin');

    expect(wrapper.emitted('update:q')).toBeTruthy();
    expect(wrapper.emitted('update:q')[0]).toEqual(['bitcoin']);

    await input.trigger('keydown.enter');
    expect(wrapper.emitted('refresh')).toBeTruthy();
  });

  it('emits refresh when clicking Refresh button', async () => {
    const wrapper = mount(QueueControls, {
      props: {
        stateFilter: '',
        q: '',
        sort: 'created_at',
        dir: 'desc',
        loading: false,
      },
    });

    await wrapper.get('button').trigger('click');
    expect(wrapper.emitted('refresh')).toBeTruthy();
  });

  it('emits updates when selects change', async () => {
    const wrapper = mount(QueueControls, {
      props: {
        stateFilter: '',
        q: '',
        sort: 'created_at',
        dir: 'desc',
        loading: false,
      },
    });

    const selects = wrapper.findAll('select');
    expect(selects.length).toBe(3);

    await selects[0].setValue('pending');
    expect(wrapper.emitted('update:stateFilter')).toBeTruthy();
    expect(wrapper.emitted('update:stateFilter')[0]).toEqual(['pending']);

    await selects[1].setValue('risk_score');
    expect(wrapper.emitted('update:sort')).toBeTruthy();
    expect(wrapper.emitted('update:sort')[0]).toEqual(['risk_score']);

    await selects[2].setValue('asc');
    expect(wrapper.emitted('update:dir')).toBeTruthy();
    expect(wrapper.emitted('update:dir')[0]).toEqual(['asc']);
  });
});
