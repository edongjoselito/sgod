import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/partner_item.dart';
import '../models/donation_item.dart';
import '../models/tax_incentive_requirement_item.dart';

/// Repository for the Adopt-A-School feature — partners, donations,
/// and tax incentive requirements.
class AdoptASchoolRepository {
  AdoptASchoolRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _partnersKey = 'aas_partners';
  static const _donationsKey = 'aas_donations';

  // ── Partners ──────────────────────────────────────────────────────────────

  Future<List<PartnerItem>> fetchPartners({String search = ''}) async {
    try {
      final query = <String, dynamic>{'limit': 200};
      if (search.isNotEmpty) query['search'] = search;
      final data = await _api.get('api/partners_index', query: query);
      if (data == null) return const [];
      final items = (data as List<dynamic>)
          .map((e) => PartnerItem.fromJson(e as Map<String, dynamic>))
          .toList();
      _cache?.set(_partnersKey, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<PartnerItem>>(_partnersKey);
      if (cached != null) return cached;
      rethrow;
    }
  }

  Future<String> savePartner(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await _api.post('api/partners_save', body: body);
    _cache?.remove(_partnersKey);
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deletePartner(String id) async {
    await _api.post('api/partners_delete', body: {'id': id});
    _cache?.remove(_partnersKey);
  }

  // ── Donations ─────────────────────────────────────────────────────────────

  Future<List<DonationItem>> fetchDonations({String partnerId = ''}) async {
    try {
      final query = <String, dynamic>{'limit': 200};
      if (partnerId.isNotEmpty) query['partner_id'] = partnerId;
      final data = await _api.get('api/donations_index', query: query);
      if (data == null) return const [];
      final items = (data as List<dynamic>)
          .map((e) => DonationItem.fromJson(e as Map<String, dynamic>))
          .toList();
      _cache?.set(_donationsKey, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<DonationItem>>(_donationsKey);
      if (cached != null) return cached;
      rethrow;
    }
  }

  Future<String> saveDonation(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await _api.post('api/donations_save', body: body);
    _cache?.remove(_donationsKey);
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deleteDonation(String id) async {
    await _api.post('api/donations_delete', body: {'id': id});
    _cache?.remove(_donationsKey);
  }

  // ── Tax Incentive Requirements ────────────────────────────────────────────

  Future<List<TaxIncentiveRequirementItem>> fetchTaxRequirements(
      String donationId) async {
    final data = await _api.get(
      'api/tax_incentive_requirements_index',
      query: {'donation_id': donationId},
    );
    if (data == null) return const [];
    return (data as List<dynamic>)
        .map((e) => TaxIncentiveRequirementItem.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<String> saveTaxRequirement(Map<String, dynamic> fields,
      {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data =
        await _api.post('api/tax_incentive_requirements_save', body: body);
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deleteTaxRequirement(String id) async {
    await _api.post('api/tax_incentive_requirements_delete', body: {'id': id});
  }

  // ── Contribution Types ────────────────────────────────────────────────────

  Future<List<String>> fetchContributionTypes() async {
    final data = await _api.get('api/contribution_types_index');
    if (data == null) return const [];
    return (data as List<dynamic>)
        .map((e) => (e as Map<String, dynamic>)['name'] as String? ?? '')
        .where((s) => s.isNotEmpty)
        .toList();
  }
}
